<?php

namespace App\Observers;

use App\Models\payment;
use App\Models\TransactionLog;
use Carbon\Carbon;

class PaymentObserver
{
    /**
     * Handle the payment "created" event.
     */
    public function created(payment $payment): void
    {
        if ($payment->payment_status === 'success') {
            $this->logTransaction($payment);
        }
    }

    /**
     * Handle the payment "updated" event.
     */
    public function updated(payment $payment): void
    {
        if ($payment->isDirty('payment_status') && $payment->payment_status === 'success') {
            $this->logTransaction($payment);
        }
    }

    /**
     * Log the transaction to TransactionLog.
     */
    private function logTransaction(payment $payment): void
    {
        $transaction = $payment->transaction;
        if (!$transaction) {
            return;
        }

        TransactionLog::create([
            'transaction_id' => $payment->transaction_id,
            'room_id' => $transaction->room_id,
            'boarding_house_id' => $transaction->room->boarding_house_id ?? null,
            'type' => 'payment',
            'amount' => $payment->amount,
            'status' => 'completed',
            'description' => 'Pembayaran ' . ($payment->payment_sequence === 'installment' ? 'Cicilan' : 'Penuh') . ' - TRX ' . $transaction->transaction_code,
            'payment_method' => $payment->payment_method,
            'reference_number' => 'PAY-' . $payment->id . '-' . time(),
            'transaction_date' => $payment->payment_date ?? now(),
        ]);
    }
}
