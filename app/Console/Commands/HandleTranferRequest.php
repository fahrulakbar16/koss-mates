<?php

namespace App\Console\Commands;

use App\Models\Expense;
use App\Models\Room;
use App\Models\RoomTransfer;
use App\Models\UserRooms;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\payment;
use App\Models\Refund;
use App\Models\TransactionLog;
use App\Models\RekapHistory;

class HandleTranferRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:handle-tranfer-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handle room transfer requests scheduled for today';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        $transfers = RoomTransfer::whereDate('plan_date', $today)
            ->where('status', 'approved')
            ->where('is_process', 0)
            ->get();

        $this->info("Found " . $transfers->count() . " transfers scheduled for today.");

        foreach ($transfers as $transfer) {
            DB::beginTransaction();
            try {
                // 1. Find New UserRoom (status 'booked') and update to 'checkin_open'
                $newUserRoom = UserRooms::where('user_id', $transfer->user_id) // Or from old userRoom->user_id
                    ->where('room_id', $transfer->room_id)
                    ->where('status', 'booked')
                    ->first();

                if ($newUserRoom) {
                    $newUserRoom->update(['status' => 'checkin_open']);
                    $this->info("Updated New UserRoom ID {$newUserRoom->id} to 'checkin_open'.");
                }

                // 2. Find Old UserRoom and update to 'checked_out'
                // The transfer record has 'user_room_id' which is the old/source user_room
                $oldUserRoom = $transfer->userRoom;

                if ($oldUserRoom) {
                    $oldUserRoom->update(['status' => 'checked_out']);
                    $this->info("Updated Old UserRoom ID {$oldUserRoom->id} to 'checked_out'.");

                    // 3. Update Old Room status to 'available'
                    $oldRoom = $oldUserRoom->room;
                    if ($oldRoom) {
                        $oldRoom->update(['status' => 'available']); // Assuming 'available' is the string value or use Room::STATUS_AVAILABLE constant if imported
                        $this->info("Updated Old Room ID {$oldRoom->id} to 'available'.");
                    }
                }

                // 4. Handle Kekurangan Pembayaran (Shortage)
                if ($transfer->kekurangan_pembayaran > 0) {
                    $transaction = Transaction::create([
                        'user_id' => $transfer->user_id,
                        'user_room_id' => $newUserRoom->id ?? null, // Link to new user room
                        'room_id' => $transfer->room_id,
                        'room_price_id' => $transfer->room_price_id,
                        'total_price' => $transfer->kekurangan_pembayaran,
                        'payment_scheme' => 'installment',
                        'type' => 'booked',
                        'status' => Transaction::STATUS_PENDING, // Or 'incomplete' based on business logic, using pending for now
                    ]);

                    $this->info("Created Transaction ID {$transaction->id} for shortage: {$transfer->kekurangan_pembayaran}");

                    if ($transfer->sisa_pembayaran > 0) {
                        $payment = payment::create([
                            'transaction_id' => $transaction->id,
                            'amount' => $transfer->sisa_pembayaran,
                            'payment_method' => 'cash',
                            'payment_status' => 'success',
                            'payment_date' => Carbon::now(),
                            'payment_sequence' => 'installment', // First payment
                        ]);

                        $this->info("Created Payment ID {$payment->id} for amount: {$transfer->sisa_pembayaran}");

                        // Create rekap history
                        $roomPrice = $transaction->roomPrice;
                        $duration = $roomPrice->duration;
                        $paymentDate = $payment->payment_date;
                        $totalPaid = $transaction->payments()->where('payment_status', 'success')->sum('amount');

                        for ($i = 0; $i < $duration; $i++) {
                            $currentDate = Carbon::parse($paymentDate)->addMonthsNoOverflow($i);
                            $transaction->userRoom->rekapHistories()->updateOrCreate(
                                [
                                    'user_room_id' => $transaction->userRoom->id,
                                    'month' => $currentDate->month,
                                    'year' => $currentDate->year,
                                ],
                                [
                                    'total_price' => $transaction->total_price,
                                    'total_payment' => $totalPaid,
                                    'payment_date' => $paymentDate,
                                    'status' => $totalPaid >= $transaction->total_price ? 'completed' : 'incomplete',
                                ]
                            );
                        }
                    }
                }

                // 5. Handle Pengembalian Dana (Refund)
                if ($transfer->pengembalian_dana > 0) {
                    $transaction = Transaction::create([
                        'user_id' => $transfer->user_id,
                        'user_room_id' => $newUserRoom->id ?? null, // Link to new user room
                        'room_id' => $transfer->room_id,
                        'room_price_id' => $transfer->room_price_id,
                        'total_price' => $transfer->price->price,
                        'payment_scheme' => 'full',
                        'type' => 'booked',
                        'status' => Transaction::STATUS_COMPLETED, // Or 'incomplete' based on business logic, using pending for now
                    ]);

                    $payment = $transaction->payments()->create([
                        'amount' => $transfer->price->price,
                        'payment_method' => 'cash',
                        'payment_status' => 'success',
                        'payment_date' => now(),
                        'payment_sequence' => 'full',
                    ]);

                    // Create rekap history
                    $roomPrice = $transaction->roomPrice;
                    $duration = $roomPrice->duration;
                    $paymentDate = $payment->payment_date;
                    $totalPaid = $transaction->payments()->where('payment_status', 'success')->sum('amount');

                    for ($i = 0; $i < $duration; $i++) {
                        $currentDate = Carbon::parse($paymentDate)->addMonthsNoOverflow($i);
                        $transaction->userRoom->rekapHistories()->updateOrCreate(
                            [
                                'user_room_id' => $transaction->userRoom->id,
                                'month' => $currentDate->month,
                                'year' => $currentDate->year,
                            ],
                            [
                                'total_price' => $transaction->total_price,
                                'total_payment' => $totalPaid,
                                'payment_date' => $paymentDate,
                                'status' => $totalPaid >= $transaction->total_price ? 'completed' : 'incomplete',
                            ]
                        );
                    }

                    $refund = Refund::create([
                        'user_id' => $transfer->user_id,
                        'boarding_house_id' => $oldUserRoom->boarding_house_id,
                        'amount' => $transfer->pengembalian_dana,
                        'status' => 'pending',
                        'is_verified' => false,
                    ]);

                    //buat pengeluaran di kos yang lama dengan status oper dana
                    Expense::create([
                        'user_id' => $transfer->user_id,
                        'boarding_house_id' => $oldUserRoom->boarding_house_id,
                        'room_id' => $oldUserRoom->room_id,
                        'expense_date' => now(),
                        'amount' => $transfer->price->price,
                        'description' => "Oper dana - pindah kamar",
                        'category' => 'pengeluaran',
                        'status' => 'selesai',
                    ]);

                    $this->info("Created Refund ID {$refund->id} for amount: {$transfer->pengembalian_dana}");
                }

                $transfer->update([
                    'is_process' => 1,
                ]);
                $this->info("Updated Transfer ID {$transfer->id} to 'is_process' = true");

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Failed to process Transfer ID {$transfer->id}: " . $e->getMessage());
                \Illuminate\Support\Facades\Log::error("Failed to process Transfer ID {$transfer->id}: " . $e->getMessage());
            }
        }

        $this->info("Transfer processing completed.");
    }
}
