<?php

namespace App\Policies;

use App\Exceptions\CustomException;
use App\Models\User;
use App\Models\UserRooms;

class UserRoomPolicy
{
    /**
     * Determine whether the user can access room active data.
     * User must have submitted check-in photo if status is 'booked'.
     */
    public function accessRoomActive(User $user, UserRooms $activeRoom): bool
    {
        // If user has booked room but hasn't uploaded check-in photo
        if ($activeRoom->status == 'booked' && empty($activeRoom->foto_kamar)) {
            // Check if request is from API
            if (request()->expectsJson() || request()->is('api/*')) {
                throw new CustomException('Anda harus melakukan check-in terlebih dahulu dengan mengupload foto kamar', 403);
            }

            // For web requests, throw exception that will be caught by handler
            throw new \Illuminate\Auth\Access\AuthorizationException(
                'Anda harus melakukan check-in terlebih dahulu dengan mengupload foto kamar'
            );
        }

        if ($activeRoom->foto_kamar && !$activeRoom->verifikasi_admin) {
            // Check if request is from API
            if (request()->expectsJson() || request()->is('api/*')) {
                throw new CustomException('Foto kamar belum diverifikasi admin', 403);
            }

            // For web requests, throw exception that will be caught by handler
            throw new \Illuminate\Auth\Access\AuthorizationException(
                'Foto kamar belum diverifikasi admin'
            );
        }

        return true;
    }
}
