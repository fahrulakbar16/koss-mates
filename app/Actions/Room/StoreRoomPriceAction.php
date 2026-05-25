<?php

namespace App\Actions\Room;

use App\Models\Room;
use App\Models\RoomPrice;
use App\Helpers\LogActivityHelper;

class StoreRoomPriceAction
{
    public function execute(Room $room, array $data): RoomPrice
    {
        $roomPrice = $room->prices()->create($data);

        LogActivityHelper::addToLog('Menambah harga kamar: ' . $room->name . ' (' . $roomPrice->duration . ' hari)', [
            'id' => $roomPrice->id,
            'room_id' => $room->id,
            'duration' => $roomPrice->duration,
            'price' => $roomPrice->price,
            'name' => $roomPrice->name,
            'addons' => $roomPrice->addons,
        ]);

        return $roomPrice;
    }
}
