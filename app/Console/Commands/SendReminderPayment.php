<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Services\FonnteApiService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendReminderPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim pesan WhatsApp pengingat untuk transaksi yang mendekati atau lewat jatuh tempo (<= 5 hari)';

    /**
     * Execute the console command.
     */
    public function handle(FonnteApiService $fonnteService)
    {
        $this->info('Memulai pengecekan tagihan untuk pengiriman pengingat...');

        // Jatuh tempo kurang dari 5 hari atau sudah lewat (<= 5 hari dari hari ini)
        $targetDate = Carbon::now()->addDays(5)->format('Y-m-d');

        $sentCount = 0;
        $failedCount = 0;
        $skippedCount = 0;

        $transactions = Transaction::query()
            ->whereIn('status', [Transaction::STATUS_PENDING, Transaction::STATUS_INCOMPLETE])
            ->whereNotNull('jatuh_tempo')
            ->whereDate('jatuh_tempo', '<=', $targetDate)
            ->select('id')->lazyById(100);

        foreach ($transactions as $candidate) {
            try {
                // Keep the row locked until the successful reminder is recorded.
                // A concurrent invocation must recheck the record after acquiring this lock.
                $sent = DB::transaction(function () use ($candidate, $fonnteService) {
                    $transaction = Transaction::query()->lockForUpdate()->find($candidate->id);
                    $today = Carbon::today();
                    if (! $transaction
                        || ! in_array($transaction->status, [Transaction::STATUS_PENDING, Transaction::STATUS_INCOMPLETE], true)
                        || ! $transaction->jatuh_tempo
                        || $transaction->jatuh_tempo->gt($today->copy()->addDays(5))
                        || ($transaction->last_reminder_sent_at && $transaction->last_reminder_sent_at->gte($today))) {
                        return false;
                    }

                    $remaining = $transaction->total_price - $transaction->paymentSuccess()->sum('amount');
                    if ($remaining <= 0) {
                        return false;
                    }

                    $transaction->load(['user.tenant', 'room.boardingHouse']);
                    $user = $transaction->user;
                    $phoneNumber = $this->normalizePhone($user?->tenant?->phone);
                    if (! $user || ! $phoneNumber) {
                        $this->warn("Skipping TRX #{$transaction->transaction_code}: Data penghuni atau nomor WhatsApp tidak valid.");

                        return false;
                    }

                    $room = $transaction->room;
                    $boardingHouse = $room->boardingHouse ?? null;
                    $boardingHouseName = $boardingHouse ? $boardingHouse->name : 'Sistem Kos';
                    $roomName = $room?->name ?? '-';

                    $jatuhTempo = Carbon::parse($transaction->jatuh_tempo);

                    // startOfDay() untuk menghitung murni perbedaan hari
                    $daysDiff = (int) $today->diffInDays($jatuhTempo->startOfDay(), false);

                    if ($daysDiff > 0) {
                        $timeStatus = "akan jatuh tempo dalam {$daysDiff} hari";
                    } elseif ($daysDiff == 0) {
                        $timeStatus = 'jatuh tempo pada *HARI INI*';
                    } else {
                        $overdueDays = abs($daysDiff);
                        $timeStatus = "telah *LEWAT JATUH TEMPO* selama {$overdueDays} hari";
                    }

                    $amount = number_format($remaining, 0, ',', '.');

                    $message = "Halo *{$user->name}*,\n\n";
                    $message .= "Ini adalah pengingat otomatis dari *{$boardingHouseName}*.\n\n";
                    $message .= "Sisa tagihan Anda untuk kamar *{$roomName}* sebesar *Rp {$amount}* {$timeStatus} (Tanggal Jatuh Tempo: {$jatuhTempo->format('d M Y')}).\n\n";
                    $message .= "Mohon segera melakukan pembayaran agar status sewa Anda tetap aktif.\n\n";
                    $message .= 'Terima kasih.';

                    $result = $fonnteService->sendMessage($phoneNumber, $message);
                    if (($result['success'] ?? false) !== true) {
                        throw new \RuntimeException($result['message'] ?? 'API menolak pengiriman pesan.');
                    }

                    $transaction->last_reminder_sent_at = now();
                    $transaction->save();
                    $this->info("✓ Pengingat diterima API untuk {$user->name} ({$phoneNumber})");

                    return true;
                });
                $sent ? $sentCount++ : $skippedCount++;
            } catch (\Throwable $e) {
                Log::error('Gagal WA reminder', ['transaction_id' => $candidate->id, 'error' => $e->getMessage()]);
                $this->error("✗ Gagal memproses transaksi #{$candidate->id}: {$e->getMessage()}");
                $failedCount++;
            }
        }

        $this->newLine();
        $this->info('=== Ringkasan Pengingat ===');
        $this->info("Diterima API: {$sentCount}");
        $this->info("Gagal: {$failedCount}");
        $this->info("Diabaikan: {$skippedCount}");

        return $failedCount > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function normalizePhone(?string $phone): ?string
    {
        $phone = preg_replace('/[\s()+-]/', '', $phone ?? '');
        $phone = preg_replace('/^0/', '62', $phone);
        if (str_starts_with($phone, '8')) {
            $phone = '62'.$phone;
        }

        return preg_match('/^628[0-9]{8,11}$/', $phone) ? $phone : null;
    }
}
