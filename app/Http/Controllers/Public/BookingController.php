<?php

namespace App\Http\Controllers\Public;

use App\Actions\Transaction\CreateTransaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaksi\BookingRequest;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class BookingController extends Controller
{
    /**
     * Store a new booking.
     */
    public function store(BookingRequest $request)
    {
        $user = Auth::user();

        // Check if user can create a booking using the policy
        Gate::authorize('createBooking');
        $transaction = app(CreateTransaction::class)->execute($user, $request->validated(), Transaction::TYPE_BOOKED);

        return redirect()->route('penyewa.transactions.show', $transaction->id);
    }
}
