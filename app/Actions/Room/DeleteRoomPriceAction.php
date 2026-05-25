<?php

namespace App\Actions\Room;

use App\Models\RoomPrice;
use App\Helpers\LogActivityHelper;

class DeleteRoomPriceAction
{
    public function execute(RoomPrice $roomPrice): void
    {
        $priceId = $roomPrice->id;
        $roomName = $roomPrice->room->name ?? 'N/A';
        $duration = $roomPrice->duration;
        $roomPrice->delete();

        LogActivityHelper::addToLog('Menghapus harga kamar: ' . $roomName . ' (' . $duration . ' hari)', [
            'id' => $priceId,
            'room_name' => $roomName,
            'duration' => $duration,
        ]);
    }
}
