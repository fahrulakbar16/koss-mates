<?php

namespace App\Http\Controllers\Penyewa;

use App\Actions\Payment\ProcessPaymentSuccess;
use App\Http\Controllers\Controller;
use App\Models\payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PaymentFinishController extends Controller
{
    public function __invoke(Request $request)
    {
        $orderId = $request->query('order_id');
        $payment = null;

        if ($orderId && preg_match('/^PAY-(\d+)-/', $orderId, $matches)) {
            $payment = payment::with('transaction')->find($matches[1]);
        }

        // Fallback: if webhook hasn't arrived yet, check status directly from Midtrans
        if ($payment && $payment->payment_status === 'pending' && $orderId) {
            $this->syncFromMidtrans($orderId, $payment);
            $payment->refresh();
        }

        return Inertia::render('Penyewa/Transactions/PaymentFinish', [
            'payment' => $payment,
            'status' => $request->query('transaction_status'),
            'orderId' => $orderId,
            'message' => $this->getMessage($request->query('transaction_status')),
        ]);
    }

    private function syncFromMidtrans(string $orderId, payment $payment): void
    {
        try {
            \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
            \Midtrans\Config::$isProduction = config('services.midtrans.is_production', false);

            $status = \Midtrans\Transaction::status($orderId);

            if (in_array($status->transaction_status, ['settlement', 'capture'])) {
                app(ProcessPaymentSuccess::class)->execute($payment);
            } elseif (in_array($status->transaction_status, ['deny', 'expire', 'cancel'])) {
                $payment->update(['payment_status' => 'failed']);
            }
        } catch (\Exception $e) {
            Log::warning('PaymentFinish: Midtrans status check failed', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function getMessage(?string $status): string
    {
        return match ($status) {
            'settlement', 'capture' => 'Pembayaran Anda berhasil diproses. Terima kasih!',
            'pending' => 'Pembayaran Anda sedang menunggu proses. Silakan selesaikan jika belum.',
            'expire' => 'Maaf, batas waktu pembayaran Anda telah berakhir.',
            'cancel', 'deny' => 'Pembayaran Anda telah dibatalkan atau ditolak.',
            default => 'Kami telah menerima informasi pembayaran Anda.',
        };
    }
}
