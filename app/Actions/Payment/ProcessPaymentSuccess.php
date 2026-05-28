<?php

namespace App\Actions\Payment;

use App\Models\Room;
use App\Models\payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessPaymentSuccess
{
    /**
     * Process a successful payment. Idempotent — safe to call multiple times.
     */
    public function execute(payment $payment): void
    {
        if ($payment->payment_status === 'success') {
            return;
        }

        DB::beginTransaction();
        try {
            $payment->payment_status = 'success';
            $payment->payment_date = now();
            $payment->save();

            $transaction = $payment->transaction;

            $totalPaid = $transaction->payments()
                ->where('payment_status', 'success')
                ->sum('amount');

            $transaction->update([
                'status' => $totalPaid >= $transaction->total_price ? 'completed' : 'incomplete',
            ]);

            $roomPrice = $transaction->roomPrice;
            $duration = $roomPrice->duration;
            $paymentDate = $payment->payment_date;

            for ($i = 0; $i < $duration; $i++) {
                $currentDate = Carbon::parse($paymentDate)->addMonthsNoOverflow($i);

                if ($transaction->type == 'booked') {
                    $transaction->userRoom->update(['status' => 'checkin_open']);
                }

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
                        'status' => 'completed',
                    ]
                );
            }

            Room::where('id', $transaction->room_id)->update(['status' => Room::STATUS_OCCUPIED]);

            DB::commit();

            $this->sendCheckinReminder($transaction);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ProcessPaymentSuccess error: ' . $e->getMessage(), ['payment_id' => $payment->id]);
            throw $e;
        }
    }

    private function sendCheckinReminder($transaction): void
    {
        try {
            $user = $transaction->user;
            if (!$user || !$user->tenant || !$user->tenant->phone) {
                return;
            }

            $userRoom = $transaction->userRoom;
            if (!$userRoom || !$userRoom->room) {
                return;
            }

            $phone = preg_replace('/^0/', '62', $user->tenant->phone);

            $message = "Halo {$user->name},\n\n" .
                "Pembayaran Anda untuk kamar {$userRoom->room->name} telah berhasil!\n\n" .
                "Silakan lakukan *check-in* dengan cara:\n" .
                "1. Login ke akun Anda\n" .
                "2. Buka menu 'Kamar Saya'\n" .
                "3. Upload foto kondisi kamar\n" .
                "4. Tunggu verifikasi dari admin\n\n" .
                "Terima kasih telah menggunakan layanan kami.";

            app(\App\Services\FonnteApiService::class)->sendMessage($phone, $message);

            Log::info('Check-in reminder sent', ['transaction_id' => $transaction->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send check-in reminder: ' . $e->getMessage(), [
                'transaction_id' => $transaction->id ?? null,
            ]);
        }
    }
}
