<?php

namespace App\Http\Controllers\Penyewa;

use App\Actions\Payment\CreatePayment;
use App\Actions\Transaction\GetPaymentListPaginate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaksi\PaymentRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = app(GetPaymentListPaginate::class)->execute(Auth::user(), $request->all());

        return Inertia::render('Penyewa/Transactions/Index', [
            'transactions' => $transactions
        ]);
    }

    public function show(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction->load([
            'room.boardingHouse.images',
            'roomPrice',
            'user',
            'payments',
            'paymentActive'
        ]);


        return Inertia::render('Penyewa/Transactions/Show', [
            'transaction' => $transaction
        ]);
    }

    public function createPayment(PaymentRequest $request, Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        if ($transaction->payments()->where('payment_status', 'pending')->sum('amount') >= $transaction->total_price) {

            $transaction->payments()->where('payment_status', 'pending')->delete();
        }

        try {
            $payment = app(CreatePayment::class)
                ->execute($transaction, $request->amount);

            return response()->json([
                'success' => true,
                'snap_token' => $payment->snap_token,
                'payment_id' => $payment->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function cancel(Transaction $transaction)
    {
        // Check ownership
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if transaction can be canceled
        if (!in_array($transaction->status, ['pending', 'incomplete'])) {
            return redirect()->back()->with('error', 'Transaksi tidak dapat dibatalkan karena sudah ' . $transaction->status);
        }

        if ($transaction->type !== 'booked') {
            return redirect()->back()->with('error', 'Hanya transaksi booking baru yang dapat dibatalkan');
        }

        // Update transaction status to cancelled
        $transaction->update(['status' => 'cancelled']);

        $transaction->room()->update([
            'status' => 'available'
        ]);

        // Cancel any pending payments
        $transaction->payments()->where('payment_status', 'pending')->update([
            'payment_status' => 'failed'
        ]);

        return redirect()->route('penyewa.transactions.index')
            ->with('success', 'Transaksi berhasil dibatalkan');
    }
}
