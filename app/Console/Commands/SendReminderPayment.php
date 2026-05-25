<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Services\FonnteApiService;
use Carbon\Carbon;
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

        $transactions = Transaction::with(['user.tenant', 'room.boardingHouse'])
            ->whereIn('status', ['pending', 'incomplete'])
            ->whereNotNull('jatuh_tempo')
            ->whereDate('jatuh_tempo', '<=', $targetDate)
            ->get();

        if ($transactions->isEmpty()) {
            $this->info('Tidak ada transaksi yang perlu dikirimkan pengingat saat ini.');
            return 0;
        }

        $sentCount = 0;
        $failedCount = 0;

        foreach ($transactions as $transaction) {
            $user = $transaction->user;
            $tenant = $user->tenant ?? null;

            if (!$tenant || !$tenant->phone) {
                $this->warn("Skipping TRX #{$transaction->transaction_code}: Nomor WhatsApp tenant tidak ditemukan untuk {$user->name}");
                $failedCount++;
                continue;
            }

            $room = $transaction->room;
            $boardingHouse = $room->boardingHouse ?? null;
            $boardingHouseName = $boardingHouse ? $boardingHouse->name : 'Sistem Kos';
            $roomName = $room ? $room->nama_kamar : '-';

            $jatuhTempo = Carbon::parse($transaction->jatuh_tempo);

            // startOfDay() untuk menghitung murni perbedaan hari
            $daysDiff = Carbon::now()->startOfDay()->diffInDays($jatuhTempo->startOfDay(), false);

            if ($daysDiff > 0) {
                $timeStatus = "akan jatuh tempo dalam {$daysDiff} hari";
            } elseif ($daysDiff == 0) {
                $timeStatus = "jatuh tempo pada *HARI INI*";
            } else {
                $overdueDays = abs($daysDiff);
                $timeStatus = "telah *LEWAT JATUH TEMPO* selama {$overdueDays} hari";
            }

            $amount = number_format($transaction->total_price, 0, ',', '.');
            $phoneNumber = preg_replace('/^0/', '62', $tenant->phone);

            $message = "Halo *{$user->name}*,\n\n";
            $message .= "Ini adalah pengingat otomatis dari *{$boardingHouseName}*.\n\n";
            $message .= "Tagihan Anda untuk kamar *{$roomName}* sebesar *Rp {$amount}* {$timeStatus} (Tanggal Jatuh Tempo: {$jatuhTempo->format('d M Y')}).\n\n";
            $message .= "Mohon segera melakukan pembayaran agar status sewa Anda tetap aktif.\n\n";
            $message .= "Terima kasih.";

            try {
                $fonnteService->sendMessage($phoneNumber, $message);
                $this->info("✓ Berhasil mengirim WA ke {$user->name} ({$phoneNumber})");
                $sentCount++;
            } catch (\Exception $e) {
                Log::error("Gagal WA reminder ke {$user->name}: " . $e->getMessage());
                $this->error("✗ Gagal WA ke {$user->name}: " . $e->getMessage());
                $failedCount++;
            }
        }

        $this->newLine();
        $this->info("=== Ringkasan Pengingat ===");
        $this->info("Pesan Terkirim: {$sentCount}");
        $this->info("Gagal / Diabaikan: {$failedCount}");

        return 0;
    }
}
