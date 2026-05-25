<?php

namespace App\Http\Controllers\Public;

use App\Actions\Notifikasi\SendNotifToUser;
use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\TransactionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;

class PaymentCallbackController extends Controller
{
    /**
     * Handle Midtrans payment notification.
     */
    public function handle(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transactionStatus = $request->transaction_status;
        $orderId = $request->order_id;

        // Extract payment ID from order_id (PAY-{id}-{timestamp})
        $parts = explode('-', $orderId);
        if (count($parts) < 2 || $parts[0] !== 'PAY') {
            return response()->json(['message' => 'Invalid order ID format'], 400);
        }
        $paymentId = $parts[1];

        $payment = \App\Models\payment::find($paymentId);

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        // Store gateway response
        DB::beginTransaction();
        try {
            $payment->gateway_response = $request->all();

            // Update payment status
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                $payment->payment_status = 'success';
                $payment->payment_date = now();
            } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                $payment->payment_status = 'failed';
            } elseif ($transactionStatus == 'pending') {
                $payment->payment_status = 'pending';
            }

            $payment->save();

            // Check if transaction is fully paid
            if ($payment->payment_status === 'success') {
                $transaction = $payment->transaction;

                $totalPaid = $transaction->payments()
                    ->where('payment_status', 'success')
                    ->sum('amount');

                if ($totalPaid >= $transaction->total_price) {
                    $transaction->update(['status' => 'completed']);
                } else {
                    $transaction->update(['status' => 'incomplete']);
                }

                // Create rekap history for each month based on duration
                $roomPrice = $transaction->roomPrice;
                $duration = $roomPrice->duration; // Duration in months
                $paymentDate = $payment->payment_date;

                // Starting from the payment date, create rekap for each month
                for ($i = 0; $i < $duration; $i++) {
                    $currentDate = \Carbon\Carbon::parse($paymentDate)->addMonthsNoOverflow($i);

                    if ($transaction->type == 'booked') {
                        $transaction->userRoom->update([
                            'status' => 'checkin_open',
                        ]);
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

                $room = Room::where('id', $transaction->room_id)->firstOrFail();
                $room->update([
                    'status' => Room::STATUS_OCCUPIED,
                ]);

                // Send WhatsApp notification to do check-in
                $this->sendCheckinReminder($transaction);

                // app(SendNotifToUser::class)->execute($transaction->user, 'Pembayaran berhasil', 'Pembayaran berhasil', ['type' => 'payment_success'], app(Messaging::class));
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment callback error: ' . $e->getMessage(), ['order_id' => $orderId]);
            return response()->json(['message' => 'Internal Server Error'], 500);
        }

        return response()->json(['message' => 'Callback handled successfully']);
    }

    /**
     * Send WhatsApp notification reminder for check-in
     */
    private function sendCheckinReminder($transaction)
    {
        try {
            $fonnteService = app(\App\Services\FonnteApiService::class);

            // Get user from transaction
            $user = $transaction->user;

            if (!$user || !$user->tenant || !$user->tenant->phone) {
                Log::warning('Cannot send check-in reminder: User or phone not found', [
                    'transaction_id' => $transaction->id,
                ]);
                return;
            }

            // Get room details from user_rooms
            $userRoom = $transaction->userRoom;

            if (!$userRoom || !$userRoom->room) {
                Log::warning('Cannot send check-in reminder: Room not found', [
                    'transaction_id' => $transaction->id,
                ]);
                return;
            }

            // Format phone number (remove leading 0, add 62)
            $formattedPhone = preg_replace('/^0/', '62', $user->tenant->phone);

            $message = "Halo {$user->name},\n\n" .
                "Pembayaran Anda untuk kamar {$userRoom->room->nama_kamar} telah berhasil!\n\n" .
                "Silakan lakukan *check-in* dengan cara:\n" .
                "1. Login ke akun Anda\n" .
                "2. Buka menu 'Kamar Saya'\n" .
                "3. Upload foto kondisi kamar\n" .
                "4. Tunggu verifikasi dari admin\n\n" .
                "Terima kasih telah menggunakan layanan kami.";

            $fonnteService->sendMessage($formattedPhone, $message);

            Log::info('Check-in reminder sent', [
                'transaction_id' => $transaction->id,
                'user_id' => $user->id,
                'phone' => $formattedPhone,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send check-in reminder: ' . $e->getMessage(), [
                'transaction_id' => $transaction->id ?? null,
            ]);
        }
    }
}
