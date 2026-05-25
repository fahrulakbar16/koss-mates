<?php

namespace App\Actions\Room;

use App\Models\User;

class GetRoomByUser
{
    /**
     * Delete a room.
     *
     * @throws \Exception
     */
    public function execute(User $user)
    {
        return $user->rooms()->with([
            'room.boardingHouse.images',
            'room.boardingHouse.owner',
            'plan',
            'transactions' => fn($query) => $query->where('status', 'pending')->latest()->limit(1),
        ])->whereIn('status', ['checkin_open', 'checked_in'])
            ->first();
    }
}
