<?php

namespace App\Console\Commands;

use App\Models\payment;
use App\Models\Transaction;
use App\Models\UserRooms;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateTransaksaction extends Command
{
    protected $signature = 'billing:create-monthly
                            {--payment-scheme=full : Payment scheme (full or installment)}
                            {--dry-run : Display what would be created without actually creating}';

    protected $description = 'Create renewal bills from the actual start date and room plan duration, starting one calendar month before due';

    public function handle(): int
    {
        $scheme = $this->option('payment-scheme');
        if (! in_array($scheme, ['full', 'installment'], true)) {
            $this->error('Invalid payment scheme. Must be "full" or "installment"');

            return self::FAILURE;
        }

        $today = CarbonImmutable::today();
        $created = $skipped = $errors = 0;
        $tenants = UserRooms::query()->where('status', 'checked_in')
            ->where('verifikasi_admin', true)->select('id')->lazyById(100);

        foreach ($tenants as $candidate) {
            try {
                $count = DB::transaction(function () use ($candidate, $today, $scheme) {
                    // Serialize billing generation for this tenancy, including the duplicate check.
                    $tenancy = UserRooms::query()->lockForUpdate()->find($candidate->id);
                    if (! $tenancy || $tenancy->status !== 'checked_in' || ! $tenancy->verifikasi_admin) {
                        return 0;
                    }
                    $tenancy->load(['plan', 'user', 'room']);
                    if (! $tenancy->start_date || ! $tenancy->plan || ! $tenancy->user || ! $tenancy->room) {
                        throw new \RuntimeException('Missing start date, plan, user, or room.');
                    }
                    $duration = (int) $tenancy->plan->duration;
                    $price = (int) $tenancy->plan->price;
                    if ($duration < 1 || $price < 1 || $tenancy->plan->room_id != $tenancy->room_id) {
                        throw new \RuntimeException('Invalid room plan duration, price, or room.');
                    }

                    $start = CarbonImmutable::parse($tenancy->start_date)->startOfDay();
                    // The initial period is covered by the booking transaction. Generate only
                    // the current renewal cycle and the upcoming cycle within the one-month advance billing window;
                    // do not backfill all historical periods for an existing tenancy.
                    $months = max(0, ($today->year - $start->year) * 12 + $today->month - $start->month);
                    $cycle = max(1, intdiv($months, $duration));
                    $count = 0;
                    for ($index = $cycle; ; $index++) {
                        // Always calculate from the original date so Jan 31 -> Feb 28 -> Mar 31.
                        $due = $start->addMonthsNoOverflow($index * $duration);
                        if ($due->subMonthNoOverflow()->gt($today)) {
                            break;
                        }
                        $nextDue = $start->addMonthsNoOverflow(($index + 1) * $duration);
                        if ($nextDue->lte($today)) {
                            continue;
                        }
                        $exists = $tenancy->transactions()
                            ->where('type', Transaction::TYPE_EXTENDED)
                            ->whereDate('jatuh_tempo', $due->toDateString())->exists();
                        if ($exists) {
                            continue;
                        }

                        if (! $this->option('dry-run')) {
                            $transaction = $tenancy->transactions()->create([
                                'user_id' => $tenancy->user_id,
                                'room_id' => $tenancy->room_id,
                                'room_price_id' => $tenancy->room_price_id,
                                'total_price' => $price,
                                'payment_scheme' => $scheme,
                                'type' => Transaction::TYPE_EXTENDED,
                                'status' => Transaction::STATUS_PENDING,
                                'jatuh_tempo' => $due->toDateString(),
                            ]);
                            $parts = $scheme === 'full' ? 1 : $duration;
                            $base = intdiv($price, $parts);
                            $remainder = $price % $parts;
                            for ($part = 0; $part < $parts; $part++) {
                                payment::create([
                                    'transaction_id' => $transaction->id,
                                    'payment_sequence' => $scheme === 'full' ? 'full' : 'installment',
                                    'amount' => $base + ($part < $remainder ? 1 : 0),
                                    'payment_method' => 'cash',
                                    'payment_status' => 'pending',
                                ]);
                            }
                        }
                        $prefix = $this->option('dry-run') ? 'Would create' : 'Prepared';
                        $this->line("{$prefix}: {$tenancy->user->name}, {$tenancy->room->name}, {$duration} month(s), Rp {$price}, due {$due->toDateString()}");
                        $count++;
                    }

                    return $count;
                });
                $created += $count;
                $skipped += $count === 0 ? 1 : 0;
            } catch (\Throwable $e) {
                $this->error("Tenancy #{$candidate->id}: {$e->getMessage()}");
                $errors++;
            }
        }

        $this->info('Billing '.($this->option('dry-run') ? 'planned' : 'created').": {$created}; tenants skipped: {$skipped}; errors: {$errors}");

        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }
}
