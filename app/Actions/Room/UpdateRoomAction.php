<?php

namespace App\Actions\Room;

use App\Models\Room;
use App\Helpers\LogActivityHelper;

class UpdateRoomAction
{
    /**
     * Update a room.
     */
    public function execute(Room $room, array $data): Room
    {
        $bh = $room->boardingHouse()->with('cluster')->first();
        $clusterName = $bh && $bh->cluster ? $bh->cluster->name : 'Kos';
        $bhNumber = $bh->number ?? '00';
        $bhNumberFormatted = str_pad($bhNumber, 2, '0', STR_PAD_LEFT);

        $roomNumber = $data['number'] ?? $room->number;
        $roomNumberFormatted = str_pad($roomNumber, 2, '0', STR_PAD_LEFT);

        $name = $clusterName . '-' . $bhNumberFormatted . '-' . $roomNumberFormatted;

        $room->update([
            'name' => $name,
            'number' => $roomNumber,
            'description' => $data['description'] ?? null,
            'capacity' => $data['capacity'] ?? 1,
            'facilities' => $data['facilities'] ?? null,
        ]);

        LogActivityHelper::addToLog('Memperbarui kamar: ' . $room->name, [
            'id' => $room->id,
            'name' => $room->name,
        ]);

        return $room->fresh();
    }
}
