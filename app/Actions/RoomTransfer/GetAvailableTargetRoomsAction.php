<?php

namespace App\Actions\RoomTransfer;

use App\Models\UserRooms;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;

class GetAvailableTargetRoomsAction
{
    /**
     * Get available rooms for transfer target.
     *
     * @param  int  $userRoomId
     * @return \Illuminate\Support\Collection
     */
    public function execute(int $userRoomId)
    {
        $userRoom = UserRooms::where('id', $userRoomId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return Room::where('boarding_house_id', $userRoom->boarding_house_id)
            ->where('id', '!=', $userRoom->room_id)
            ->where('status', Room::STATUS_AVAILABLE)
            ->with('prices')
            ->get()
            ->map(function ($room) {
                return [
                    'id' => $room->id,
                    'name' => $room->name,
                    'type' => $room->type ?? 'Standard',
                    'price' => $room->min_price,
                    'prices' => $room->prices,
                ];
            });
    }
}
