<?php

namespace App\Policies\Transaction;

use App\Exceptions\CustomException;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class TransactionPolicy
{
    /**
     * Determine whether the user can create a booking.
     * Throws exception if called from API, returns bool if called from web.
     */
    public function createBooking(User $user): bool
    {
        $hasExistingRoom = $user->rooms()->whereIn("status", ['checkin_open', 'checked_in'])->exists();

        if ($hasExistingRoom) {
            // Check if request is from API
            if (request()->expectsJson() || request()->is('api/*')) {
                throw new CustomException('Anda sudah memiliki kamar aktif. Tidak dapat melakukan booking baru.', 403);
            }

            // For web requests, throw exception that will be caught by handler
            throw new \Illuminate\Auth\Access\AuthorizationException(
                'Anda sudah memiliki kamar aktif. Tidak dapat melakukan booking baru.'
            );
        }

        return true;
    }

    /**
     * Determine whether the user can extend their booking.
     * Throws exception if called from API, returns bool if called from web.
     */
    public function createExtend(User $user): bool
    {
        $isPenyewa = $user->hasRole('penyewa');

        if (!$isPenyewa) {
            // Check if request is from API
            if (request()->expectsJson() || request()->is('api/*')) {
                throw new CustomException('Hanya penyewa yang dapat melakukan perpanjangan booking.', 403);
            }

            // For web requests, throw exception that will be caught by handler
            throw new \Illuminate\Auth\Access\AuthorizationException(
                'Hanya penyewa yang dapat melakukan perpanjangan booking.'
            );
        }

        return true;
    }
}
