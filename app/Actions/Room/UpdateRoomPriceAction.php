<?php

namespace App\Actions\Room;

use App\Models\RoomPrice;
use App\Helpers\LogActivityHelper;

class UpdateRoomPriceAction
{
    public function execute(RoomPrice $roomPrice, array $data): RoomPrice
    {
        $roomPrice->update($data);

        LogActivityHelper::addToLog('Memperbarui harga kamar: ' . ($roomPrice->room->name ?? 'N/A') . ' (' . $roomPrice->duration . ' hari)', [
            'id' => $roomPrice->id,
            'room_id' => $roomPrice->room_id,
            'duration' => $roomPrice->duration,
            'price' => $roomPrice->price,
            'name' => $roomPrice->name,
            'addons' => $roomPrice->addons,
        ]);

        return $roomPrice;
    }
}
