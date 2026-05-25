<?php

namespace App\Actions\RoomTransfer;

use App\Models\RoomTransfer;
use Illuminate\Http\Request;

class GetPendingRequest
{
    /**
     * Create a new room transfer request.
     *
     * @param  array  $data
     * @return \App\Models\RoomTransfer
     */
    public function execute($userRoomsId)
    {
        return RoomTransfer::with(['room.boardingHouse', 'userRoom.room.boardingHouse', 'userRoom.boardingHouse'])
            ->whereIn('user_room_id', $userRoomsId)
            ->where(function ($q) {
                $q->where('status', 'pending')
                    ->orWhere('status', 'approved');
            })
            ->first();
    }
}
