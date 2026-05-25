<?php

namespace App\Actions\Room;

use App\Models\Room;
use App\Helpers\LogActivityHelper;

class DeleteRoomAction
{
    /**
     * Delete a room.
     *
     * @throws \Exception
     */
    public function execute(Room $room): void
    {
        // Check if room has transactions
        if ($room->transactions()->count() > 0) {
            throw new \Exception('Tidak dapat menghapus kamar yang masih memiliki transaksi');
        }

        $roomName = $room->name;
        $roomId = $room->id;
        $room->delete();

        LogActivityHelper::addToLog('Menghapus kamar: ' . $roomName, [
            'id' => $roomId,
            'name' => $roomName,
        ]);
    }
}
