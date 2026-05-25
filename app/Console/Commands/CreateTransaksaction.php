<?php

namespace App\Console\Commands;

use App\Models\UserRooms;
use App\Models\Transaction;
use App\Models\payment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateTransaksaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:create-monthly
                            {--payment-scheme=full : Payment scheme (full or installment)}
                            {--dry-run : Display what would be created without actually creating}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create monthly billing transactions for active tenants based on their room price';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting monthly billing generation...');
        $this->newLine();

        $paymentScheme = $this->option('payment-scheme');
        $dryRun = $this->option('dry-run');

        // Validate payment scheme
        if (!in_array($paymentScheme, ['full', 'installment'])) {
            $this->error('Invalid payment scheme. Must be "full" or "installment"');
            return 1;
        }

        // Get current month and year
        $currentMonth = Carbon::now()->format('Y-m');

        // Get all active tenants (checked_in)
        $activeTenants = UserRooms::with(['user', 'room', 'plan', 'boardingHouse', 'rekapHistories' => function ($query) {
            $query->orderBy('year', 'desc')->orderBy('month', 'desc');
        }])
            ->where('status', 'checked_in')
            ->where('verifikasi_admin', true)
            ->get();

        if ($activeTenants->isEmpty()) {
            $this->warn('No active tenants found.');
            return 0;
        }

        $this->info("Found {$activeTenants->count()} active tenant(s)");
        $this->newLine();

        $created = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($activeTenants as $userRoom) {
            try {
                // Check if tenant has valid data
                if (!$userRoom->plan) {
                    $this->warn("Skipping tenant {$userRoom->user->name}: No room price plan found");
                    $errors++;
                    continue;
                }


                // Cek rekap histori: jika history month terakhir sama dengan bulan sekarang
                $latestHistory = $userRoom->rekapHistories->first();
                $currentDate = Carbon::now();

                if (!$latestHistory || $latestHistory->month != $currentDate->month || $latestHistory->year != $currentDate->year) {
                    $this->warn("Skipping {$userRoom->user->name}: Rekap history bulan ini belum ada atau bukan bulan sekarang");
                    $skipped++;
                    continue;
                }

                // Target transaksi: bulan depan
                $targetDate = $currentDate->copy()->addMonthNoOverflow();
                $billingMonth = $targetDate->month;
                $billingYear = $targetDate->year;

                // Jatuh tempo: bulan depan di tanggal yang sama saat dia masuk (start_date)
                $startDay = Carbon::parse($userRoom->start_date)->day;
                // Cegah overflow tanggal (misal masuk tgl 31, bulan depan cuma ada 30 hari)
                $daysInTargetMonth = $targetDate->daysInMonth;
                $safeStartDay = min($startDay, $daysInTargetMonth);

                $dueDate = Carbon::create($billingYear, $billingMonth, $safeStartDay);

                // Check if billing already exists for the target billing month
                $existingTransaction = Transaction::where('user_id', $userRoom->user_id)
                    ->where('room_id', $userRoom->room_id)
                    ->where('user_room_id', $userRoom->id)
                    ->whereMonth('created_at', $billingMonth)
                    ->whereYear('created_at', $billingYear)
                    ->where('type', Transaction::TYPE_EXTENDED)
                    ->first();


                if ($existingTransaction) {
                    $this->warn("Skipping {$userRoom->user->name}: Billing already exists for target month {$billingYear}-{$billingMonth}");
                    $skipped++;
                    continue;
                }

                $roomPrice = $userRoom->plan->price;
                $duration = $userRoom->plan->duration;

                if ($dryRun) {
                    $this->line("Would create billing for:");
                    $this->line("  - Tenant: {$userRoom->user->name}");
                    $this->line("  - Boarding House: {$userRoom->boardingHouse->name}");
                    $this->line("  - Room: {$userRoom->room->name}");
                    $this->line("  - Duration: {$duration} month(s)");
                    $this->line("  - Amount: Rp " . number_format($roomPrice, 0, ',', '.'));
                    $this->line("  - Payment Scheme: {$paymentScheme}");
                    $this->line("  - Target Month: {$billingYear}-{$billingMonth}");
                    $this->line("  - Jatuh Tempo: {$dueDate->format('Y-m-d')}");
                    $this->newLine();
                    $created++;
                    continue;
                }

                // Create transaction
                $transaction = Transaction::create([
                    'user_id' => $userRoom->user_id,
                    'room_id' => $userRoom->room_id,
                    'user_room_id' => $userRoom->id,
                    'room_price_id' => $userRoom->room_price_id,
                    'total_price' => $roomPrice,
                    'payment_scheme' => $paymentScheme,
                    'type' => Transaction::TYPE_EXTENDED,
                    'status' => Transaction::STATUS_PENDING,
                    'jatuh_tempo' => $dueDate->format('Y-m-d'),
                    'created_at' => Carbon::createFromDate($billingYear, $billingMonth, 1), // Override created_at to mark for that month
                ]);

                // Create payment record(s)
                if ($paymentScheme === 'full') {
                    // Single full payment
                    payment::create([
                        'transaction_id' => $transaction->id,
                        'payment_sequence' => 'full',
                        'amount' => $roomPrice,
                        'payment_method' => 'cash',
                        'payment_status' => 'pending',
                    ]);
                } else {
                    // Installment payments (split into multiple payments based on duration)
                    $installmentAmount = ceil($roomPrice / $duration);

                    for ($i = 1; $i <= $duration; $i++) {
                        payment::create([
                            'transaction_id' => $transaction->id,
                            'payment_sequence' => 'installment',
                            'amount' => $installmentAmount,
                            'payment_method' => 'cash',
                            'payment_status' => 'pending',
                        ]);
                    }
                }

                $this->info("✓ Created billing for {$userRoom->user->name} - Rp " . number_format($roomPrice, 0, ',', '.'));
                $created++;
            } catch (\Exception $e) {
                $this->error("✗ Error creating billing for {$userRoom->user->name}: {$e->getMessage()}");
                $errors++;
            }
        }

        // Summary
        $this->newLine();
        $this->info('=== Summary ===');
        $this->info("Total active tenants: {$activeTenants->count()}");
        $this->info("Billing created: {$created}");
        $this->warn("Skipped (already exists): {$skipped}");
        if ($errors > 0) {
            $this->error("Errors: {$errors}");
        }

        if ($dryRun) {
            $this->newLine();
            $this->comment('Dry run mode - No changes were made to the database');
        }

        return 0;
    }
}
