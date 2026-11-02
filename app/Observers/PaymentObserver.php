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

        if ($payment->wasChanged(['amount', 'payment_date', 'payment_method', 'payment_status'])) {
            $logs = TransactionLog::where('transaction_id', $payment->transaction_id)->where('type', 'payment');
            // A legacy log without a reference is only unambiguous with one payment.
            $singlePayment = payment::where('transaction_id', $payment->transaction_id)->count() === 1;
            $logs->where(function ($query) use ($payment, $singlePayment) {
                $query->where('reference_number', 'like', 'PAY-' . $payment->id . '-%');
                if ($singlePayment) {
                    $query->orWhereNull('reference_number')->orWhere('reference_number', '');
                }
            })->update([
                'amount' => $payment->amount,
                'payment_method' => $payment->payment_method,
                'transaction_date' => $payment->payment_date ?? now(),
                'status' => $payment->payment_status === 'success' ? 'completed' : $payment->payment_status,
            ]);
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
