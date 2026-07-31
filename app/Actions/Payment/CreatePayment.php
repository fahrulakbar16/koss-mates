<?php

namespace App\Actions\Payment;

use App\Actions\Notifikasi\SendNotifToUser;
use App\Exceptions\CustomException;
use App\Models\payment;
use App\Models\Transaction;
use App\Helpers\LogActivityHelper;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Messaging;

class CreatePayment
{
    public function execute(Transaction $transaction, $amount)
    {
        try {
            DB::beginTransaction();

            // Calculate next payment sequence
            $paidAmount = $transaction->payments()
                ->where('payment_status', 'success')
                ->sum('amount');

            $remainingAmount = $transaction->total_price - $paidAmount;
            Log::info('Remaining amount: ' . $remainingAmount);

            // Validate amount - reject if exceeds remaining balance
            if ($amount > $remainingAmount) {
                throw new CustomException(
                    'Jumlah pembayaran melebihi sisa tagihan. Sisa tagihan: Rp ' . number_format($remainingAmount, 0, ',', '.'),
                    400
                );
            }

            if ($remainingAmount == 0) {
                $paymentSequence = "full";
            } else {
                $paymentSequence = "installment";
            }

            // Create payment record
            $payment = payment::create([
                'transaction_id' => $transaction->id,
                'payment_sequence' => $paymentSequence,
                'amount' => $amount,
                'payment_method' => 'gateway',
                'payment_status' => 'pending',
            ]);

            // Generate Midtrans Snap Token
            [$snapToken, $orderId] = $this->generateSnapToken($payment);

            $payment->update(['snap_token' => $snapToken, 'order_id' => $orderId]);

            DB::commit();

            LogActivityHelper::addToLog('Membuat riwayat pembayaran: ' . ($payment->transaction->user->name ?? 'N/A'), [
                'id' => $payment->id,
                'transaction_id' => $transaction->id,
                'amount' => $amount,
                'payment_sequence' => $paymentSequence,
            ]);

            return $payment;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function generateSnapToken(payment $payment): array
    {
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = true;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
        \Midtrans\Config::$overrideNotifUrl = route('midtrans.callback');

        $orderId = 'PAY-' . $payment->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $payment->amount,
            ],
            'customer_details' => [
                'first_name' => $payment->transaction->user->name,
                'email' => $payment->transaction->user->email,
            ],
            'item_details' => [
                [
                    'id' => 'payment-' . $payment->id,
                    'price' => $payment->amount,
                    'quantity' => 1,
                    'name' => 'Pembayaran #' . $payment->payment_sequence,
                ]
            ],
            'callbacks' => [
                'finish' => route('penyewa.payment.finish'),
                'error' => route('penyewa.payment.finish'),
                'pending' => route('penyewa.payment.finish'),
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return [$snapToken, $orderId];
    }
}
