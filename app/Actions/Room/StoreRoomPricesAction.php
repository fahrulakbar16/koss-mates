<?php

namespace App\Actions\Room;

use App\Models\Room;
use Illuminate\Support\Facades\DB;
use App\Helpers\LogActivityHelper;

class StoreRoomPricesAction
{
    /**
     * Store or update room prices based on duration.
     */
    public function execute(Room $room, array $prices): void
    {
        DB::transaction(function () use ($room, $prices) {
            // Serialize updates for this room and preserve existing price IDs.
            Room::whereKey($room->id)->lockForUpdate()->firstOrFail();

            foreach ($prices as $priceData) {
                if (!isset($priceData['price']) || $priceData['price'] <= 0) {
                    continue;
                }

                $price = !empty($priceData['id'])
                    ? $room->prices()->whereKey($priceData['id'])->firstOrFail()
                    : $room->prices()->firstOrNew(['duration' => $priceData['duration']]);

                $price->fill([
                    'duration' => $priceData['duration'],
                    'price' => $priceData['price'],
                    'name' => $priceData['name'] ?? null,
                    'addons' => $priceData['addons'] ?? null,
                ])->save();
            }
        });

        LogActivityHelper::addToLog('Memperbarui daftar harga kamar: ' . $room->name, [
            'room_id' => $room->id,
            'room_name' => $room->name,
            'prices' => $prices,
        ]);
    }
}
