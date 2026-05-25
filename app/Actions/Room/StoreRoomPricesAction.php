<?php

namespace App\Actions\Room;

use App\Models\Room;
use App\Models\RoomPrice;
use App\Helpers\LogActivityHelper;

class StoreRoomPricesAction
{
    /**
     * Store or update room prices based on duration.
     */
    public function execute(Room $room, array $prices): void
    {
        // Delete all existing prices for this room
        $room->prices()->delete();

        // Create new prices
        foreach ($prices as $priceData) {
            if (isset($priceData['price']) && $priceData['price'] > 0) {
                RoomPrice::create([
                    'room_id' => $room->id,
                    'duration' => $priceData['duration'],
                    'price' => $priceData['price'],
                    'name' => $priceData['name'] ?? null,
                    'addons' => $priceData['addons'] ?? null,
                ]);
            }
        }

        LogActivityHelper::addToLog('Memperbarui daftar harga kamar: ' . $room->name, [
            'room_id' => $room->id,
            'room_name' => $room->name,
            'prices' => $prices,
        ]);
    }
}
