<?php

namespace App\Actions\Room;

use App\Models\BoardingHouse;
use App\Models\Room;
use App\Models\Transaction;
use App\Helpers\LogActivityHelper;
use Illuminate\Support\Facades\DB;

class CancelBookingAction
{
    /**
     * Cancel a room booking.
     */
    public function execute(BoardingHouse $boardingHouse, Room $room): void
    {
        if ($room->status !== Room::STATUS_BOOKED) {
            throw new \Exception('Kamar tidak dalam status booked');
        }

        DB::transaction(function () use ($boardingHouse, $room) {
            // Find the active booked transaction
            $transaction = Transaction::where('room_id', $room->id)
                ->where('type', Transaction::TYPE_BOOKED)
                ->whereIn('status', [Transaction::STATUS_PENDING, Transaction::STATUS_INCOMPLETE])
                ->latest()
                ->first();

            if ($transaction) {
                // Cancel transaction
                $transaction->update(['status' => Transaction::STATUS_CANCELLED]);

                // Cancel user room assignment
                if ($transaction->userRoom) {
                    $transaction->userRoom->update(['status' => 'cancelled']);
                }

                // Calculate successful payments
                $totalPaid = $transaction->payments()->where('payment_status', 'success')->sum('amount');

                if ($totalPaid > 0) {
                    // Create refund expense
                    app(\App\Actions\Transaction\StoreExpense::class)->execute([
                        'boarding_house_id' => $boardingHouse->id,
                        'room_id' => $room->id,
                        'amount' => $totalPaid,
                        'expense_date' => now()->format('Y-m-d'),
                        'description' => 'Pembatalan Booking & Pengembalian Dana: ' . $room->name . ' (' . $transaction->transaction_code . ')',
                        'category' => 'Pembatalan Booking',
                    ]);
                }
            }

            // Set room back to available
            $room->update(['status' => Room::STATUS_AVAILABLE]);

            LogActivityHelper::addToLog('Membatalkan booking kamar: ' . $room->name . ' di ' . $boardingHouse->name, [
                'room_id' => $room->id,
                'boarding_house_id' => $boardingHouse->id,
                'transaction_id' => $transaction ? $transaction->id : null,
            ]);
        });
    }
}
