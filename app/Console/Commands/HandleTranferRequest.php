<?php

namespace App\Console\Commands;

use App\Models\Expense;
use App\Models\Refund;
use App\Models\Room;
use App\Models\RoomTransfer;
use App\Models\Transaction;
use App\Models\UserRooms;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HandleTranferRequest extends Command
{
    protected $signature = 'app:handle-tranfer-request';

    protected $description = 'Process approved room transfers due today or overdue';

    public function handle(): int
    {
        $today = Carbon::today();
        $errors = 0;
        $transfers = RoomTransfer::whereDate('plan_date', '<=', $today)
            ->where('status', 'approved')->where('is_process', 0)
            ->select('id')->lazyById(100);

        foreach ($transfers as $candidate) {
            try {
                $processed = DB::transaction(function () use ($candidate, $today) {
                    $transfer = RoomTransfer::lockForUpdate()->find($candidate->id);
                    if (! $transfer || $transfer->status !== 'approved' || $transfer->is_process
                        || ! $transfer->plan_date || Carbon::parse($transfer->plan_date)->gt($today->copy()->endOfDay())) {
                        return false;
                    }
                    $old = UserRooms::lockForUpdate()->find($transfer->user_room_id);
                    $targets = UserRooms::where('user_id', $transfer->user_id)
                        ->where('room_id', $transfer->room_id)->where('status', 'booked')
                        ->lockForUpdate()->get();
                    if (! $old || $old->user_id != $transfer->user_id || $old->status === 'checked_out'
                        || $targets->count() !== 1 || $old->room_id == $transfer->room_id) {
                        throw new \RuntimeException('Invalid source tenancy or missing/ambiguous booked destination tenancy.');
                    }
                    $new = $targets->first();
                    $rooms = Room::whereIn('id', [$old->room_id, $new->room_id])->orderBy('id')->lockForUpdate()->get()->keyBy('id');
                    $oldRoom = $rooms->get($old->room_id);
                    $newRoom = $rooms->get($new->room_id);
                    $plan = $transfer->price;
                    if (! $oldRoom || ! $newRoom || ! $plan || $plan->room_id != $newRoom->id
                        || $plan->duration < 1 || $plan->price <= 0
                        || ! in_array($newRoom->status, [Room::STATUS_AVAILABLE, Room::STATUS_BOOKED], true)) {
                        throw new \RuntimeException('Invalid destination room or selected price plan.');
                    }
                    if (UserRooms::where('room_id', $new->room_id)->where('id', '!=', $new->id)
                        ->whereIn('status', ['booked', 'checkin_open', 'checked_in'])->exists()) {
                        throw new \RuntimeException('Destination room has another active reservation or occupant.');
                    }

                    $price = (int) $plan->price;
                    $credit = (int) $transfer->sisa_pembayaran;
                    $shortage = (int) $transfer->kekurangan_pembayaran;
                    $refund = (int) $transfer->pengembalian_dana;
                    if (min($credit, $shortage, $refund) < 0
                        || $shortage !== max(0, $price - $credit)
                        || $refund !== max(0, $credit - $price)) {
                        throw new \RuntimeException('Transfer amounts do not match the selected price and carried balance; review the approved calculation.');
                    }
                    $paid = min($credit, $price);
                    $date = Carbon::parse($transfer->plan_date)->startOfDay();
                    $status = $paid >= $price ? Transaction::STATUS_COMPLETED
                        : ($paid > 0 ? Transaction::STATUS_INCOMPLETE : Transaction::STATUS_PENDING);
                    $new->update([
                        'room_price_id' => $plan->id,
                        'status' => 'checkin_open',
                        'planned_checkin_date' => $date,
                    ]);
                    $old->update(['status' => 'checked_out', 'end_date' => $date]);
                    $oldRoom->update(['status' => Room::STATUS_AVAILABLE]);
                    $newRoom->update(['status' => Room::STATUS_BOOKED]);

                    $transaction = $new->transactions()->create([
                        'user_id' => $transfer->user_id,
                        'room_id' => $new->room_id,
                        'room_price_id' => $plan->id,
                        'total_price' => $price,
                        'payment_scheme' => $shortage > 0 ? 'installment' : 'full',
                        'type' => Transaction::TYPE_BOOKED,
                        'status' => $status,
                        'jatuh_tempo' => $date,
                        'planned_checkin_date' => $date,
                    ]);
                    if ($paid > 0) {
                        $transaction->payments()->create([
                            'amount' => $paid,
                            'payment_method' => 'cash',
                            'payment_status' => 'success',
                            'payment_date' => $date,
                            'payment_sequence' => $shortage > 0 ? 'installment' : 'full',
                        ]);
                    }
                    for ($i = 0; $i < $plan->duration; $i++) {
                        $month = $date->copy()->addMonthsNoOverflow($i);
                        $new->rekapHistories()->updateOrCreate([
                            'month' => $month->month, 'year' => $month->year,
                        ], [
                            'total_price' => $price,
                            'total_payment' => $paid,
                            'payment_date' => $paid > 0 ? $date : null,
                            'status' => $paid >= $price ? 'completed' : 'incomplete',
                        ]);
                    }
                    if ($refund > 0) {
                        Refund::create([
                            'user_id' => $transfer->user_id,
                            'boarding_house_id' => $old->boarding_house_id,
                            'amount' => $refund, 'status' => 'pending', 'is_verified' => false,
                        ]);
                    }
                    if ($paid > 0) {
                        Expense::create([
                            'user_id' => $transfer->user_id,
                            'boarding_house_id' => $old->boarding_house_id,
                            'room_id' => $old->room_id,
                            'expense_date' => $date, 'amount' => $paid,
                            'description' => 'Oper dana - pindah kamar',
                            'category' => 'pengeluaran', 'status' => 'selesai',
                        ]);
                    }
                    $transfer->update(['is_process' => 1]);

                    return true;
                });
                if ($processed) {
                    $this->info("Processed transfer #{$candidate->id}.");
                }
            } catch (\Throwable $e) {
                $errors++;
                $this->error("Failed transfer #{$candidate->id}: {$e->getMessage()}");
                Log::error('Room transfer failed', ['transfer_id' => $candidate->id, 'error' => $e->getMessage()]);
            }
        }

        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }
}
