<?php

namespace App\Http\Controllers\Public;

use App\Actions\Payment\ProcessPaymentSuccess;
use App\Http\Controllers\Controller;
use App\Models\payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $orderId = $request->order_id;
        $transactionStatus = $request->transaction_status;

        // Extract payment ID from order_id (PAY-{id}-{timestamp})
        if (!preg_match('/^PAY-(\d+)-/', $orderId, $matches)) {
            return response()->json(['message' => 'Invalid order ID format'], 400);
        }
        $paymentId = $matches[1];

        $payment = payment::find($paymentId);
        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        try {
            $payment->gateway_response = $request->all();
            $payment->save();

            if (in_array($transactionStatus, ['capture', 'settlement'])) {
                app(ProcessPaymentSuccess::class)->execute($payment);
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $payment->update(['payment_status' => 'failed']);
            } elseif ($transactionStatus === 'pending') {
                $payment->update(['payment_status' => 'pending']);
            }
        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage(), ['order_id' => $orderId]);
            return response()->json(['message' => 'Internal Server Error'], 500);
        }

        return response()->json(['message' => 'Callback handled successfully']);
    }
}
