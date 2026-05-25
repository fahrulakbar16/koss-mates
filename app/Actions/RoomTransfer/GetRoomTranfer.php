<?php

namespace App\Actions\RoomTransfer;

use App\Models\RoomTransfer;
use Illuminate\Http\Request;

class GetRoomTranfer
{
    /**
     * Create a new room transfer request.
     *
     * @param  array  $data
     * @return \App\Models\RoomTransfer
     */
    public function execute($user, $activeRoom)
    {
        return RoomTransfer::with(['room.boardingHouse', 'userRoom.room.boardingHouse', 'userRoom.boardingHouse'])
            ->where([
                'user_room_id' => $activeRoom->id,
                'user_id' => $user->id,
            ])
            ->latest()
            ->get();
    }
}
