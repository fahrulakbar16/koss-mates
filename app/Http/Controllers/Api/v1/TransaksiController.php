<?php

namespace App\Http\Controllers\Api\v1;

use App\Actions\Payment\CreatePayment;
use App\Actions\Transaction\CreateTransaction;
use App\Actions\Transaction\GetPaymentListPaginate;
use App\Actions\Transaction\GetTransactionById;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\GetTransactionListRequest;
use App\Http\Requests\Transaksi\BookingRequest;
use App\Http\Requests\Transaksi\PaymentRequest;
use App\Http\Resources\Payment\PaymentResource;
use App\Http\Resources\Transaction\TransactionDetailResource;
use App\Http\Resources\Transaction\TransactionResource;
use App\Models\Transaction;
use App\Models\UserRooms;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class TransaksiController extends Controller
{
    use AuthorizesRequests;
    use \App\Traits\ApiResponse;


    /**
     * Menampilkan daftar tagihan yang dimiliki user.
     *
     * **Authorization:** Bearer Token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(GetTransactionListRequest $request)
    {
        $transactions = app(GetPaymentListPaginate::class)->execute(Auth::user(), $request->all());

        return $this->apiResponse(TransactionResource::collection($transactions)->response()->getData(true), 'Data tagihan berhasil diambil');
    }

    /**
     * Menampilkan detail tagihan berdasarkan ID.
     *
     * **Authorization:** Bearer Token.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $transaction = app(GetTransactionById::class)->execute(Auth::user(), $id);

        Log::info($transaction->user_id);
        Log::info("Auth id: " . Auth::id());

        if ($transaction->user_id != Auth::id()) {
            throw new CustomException('Anda tidak memiliki akses untuk tagihan ini', 403);
        }

        return $this->apiResponse(
            new TransactionDetailResource($transaction),
            'Detail tagihan berhasil diambil'
        );
    }

    /**
     * Membuat booking baru (Booking Kos).
     *
     * **Authorization:** Bearer Token.
     *
     * @param BookingRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function booking(BookingRequest $request, $boardingHouseId)
    {
        $this->authorize('createBooking');

        $transaction = app(CreateTransaction::class)->execute(Auth::user(), $request->validated() + ['boarding_house_id' => $boardingHouseId], Transaction::TYPE_BOOKED);

        return response()->json([
            'message' => 'Tagihan berhasil dibuat',
        ], 201);
    }


    /**
     * Melakukan Pembayaran.
     *
     * **Authorization:** Bearer Token.
     *
     * @param BookingRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function payment(PaymentRequest $request, $transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);

        if ($transaction->user_id != Auth::id()) {
            throw new CustomException('Anda tidak memiliki akses untuk tagihan ini', 403);
        }

        // Check if there's any pending payment
        if ($transaction->payments()->where('payment_status', 'pending')->exists()) {
            $transaction->payments()->where('payment_status', 'pending')->delete();
        }

        if ($transaction->payments()->where('payment_status', 'success')->sum('amount') >= $transaction->total_price) {
            throw new CustomException('Tagihan sudah dibayar', 400);
        }

        $payment = app(CreatePayment::class)->execute($transaction, $request->amount);

        return response()->json([
            'message' => 'Pembayaran Telah Dibuat',
            'data' => new PaymentResource($payment),
        ], 201);
    }

    /**
     * Membatalkan booking.
     *
     * **Authorization:** Bearer Token.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelBooking($id)
    {
        $transaction = Transaction::findOrFail($id);


        if ($transaction->user_id !== Auth::id()) {
            throw new CustomException('Anda tidak memiliki akses untuk tagihan ini', 403);
        }

        // Only allow cancellation for booked transactions with no successful payments
        if ($transaction->type !== Transaction::TYPE_BOOKED) {
            throw new CustomException('Hanya booking yang bisa dibatalkan', 400);
        }

        $successfulPayments = $transaction->payments()->where('payment_status', 'success')->count();
        if ($successfulPayments > 0) {
            throw new CustomException('Tidak dapat membatalkan booking yang sudah memiliki pembayaran', 400);
        }

        // Cancel all pending payments
        $transaction->payments()->where('payment_status', 'pending')->delete();

        $userRoom = UserRooms::where('user_id', $transaction->user_id)->where('room_id', $transaction->room_id)->where("status", "booked")->first();

        $userRoom->update([
            'status' => 'cancelled',
        ]);

        // Delete the transaction
        $transaction->update([
            'status' => 'cancelled',
        ]);

        $userRoom->room->update([
            'status' => 'available',
        ]);



        return response()->json([
            'message' => 'Booking berhasil dibatalkan',
        ], 200);
    }

    /**
     * Mendapatkan histori pembayaran.
     *
     * **Authorization:** Bearer Token.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function paymentHistory($id)
    {
        $transaction = Transaction::findOrFail($id);

        if ($transaction->user_id !== Auth::id()) {
            throw new CustomException('Anda tidak memiliki akses untuk tagihan ini', 403);
        }

        $payments = $transaction->payments()->orderBy('created_at', 'desc')->get();

        return $this->apiResponse($payments, 'Histori pembayaran berhasil diambil');
    }
}
