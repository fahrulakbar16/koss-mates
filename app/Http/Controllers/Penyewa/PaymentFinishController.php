<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use App\Models\payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentFinishController extends Controller
{
    /**
     * Handle the redirect from Midtrans after payment.
     */
    public function __invoke(Request $request)
    {
        $orderId = $request->query('order_id');
        $paymentId = null;
        $transactionId = null;

        // Try to extract payment ID from order_id (Format: PAY-ID-TIME)
        if ($orderId && preg_match('/PAY-(\d+)-/', $orderId, $matches)) {
            $paymentId = $matches[1];
        }

        $payment = null;
        if ($paymentId) {
            $payment = payment::with('transaction')->find($paymentId);
        }

        return Inertia::render('Penyewa/Transactions/PaymentFinish', [
            'payment' => $payment,
            'status' => $request->query('transaction_status'), // Provided by Midtrans in redirect if configured
            'orderId' => $orderId,
            'message' => $this->getMessage($request->query('transaction_status'))
        ]);
    }

    /**
     * Get a user-friendly message based on Midtrans status.
     */
    private function getMessage($status)
    {
        switch ($status) {
            case 'settlement':
            case 'capture':
                return 'Pembayaran Anda berhasil diproses. Terima kasih!';
            case 'pending':
                return 'Pembayaran Anda sedang menunggu proses. Silakan selesaikan jika belum.';
            case 'expire':
                return 'Maaf, batas waktu pembayaran Anda telah berakhir.';
            case 'cancel':
            case 'deny':
                return 'Pembayaran Anda telah dibatalkan atau ditolak.';
            default:
                return 'Kami telah menerima informasi pembayaran Anda.';
        }
    }
}
