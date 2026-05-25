<?php

namespace App\Actions\Room;

use App\Models\Room;
use App\Helpers\LogActivityHelper;

class StoreRoomAction
{
    /**
     * Store a new room.
     */
    public function execute(array $data): Room
    {
        $existingRoom = Room::where('boarding_house_id', $data['boarding_house_id'])
            ->where('number', $data['number'])
            ->first();

        if ($existingRoom) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'number' => ['Nomor kamar ' . $data['number'] . ' sudah dipakai.'],
            ]);
        }

        $bh = \App\Models\BoardingHouse::with('cluster')->find($data['boarding_house_id']);
        $clusterName = $bh && $bh->cluster ? $bh->cluster->name : 'Kos';


        $bhNumber = $bh?->number ?? '00';
        $bhNumberFormatted = str_pad($bhNumber, 2, '0', STR_PAD_LEFT);

        $roomNumber = $data['number'];
        $roomNumberFormatted = str_pad($roomNumber, 2, '0', STR_PAD_LEFT);

        $name = $clusterName . '-' . $bhNumberFormatted . '-' . $roomNumberFormatted;

        $room = Room::create([
            'boarding_house_id' => $data['boarding_house_id'],
            'name' => $name,
            'number' => $roomNumber,
            'description' => $data['description'] ?? null,
            'capacity' => $data['capacity'] ?? 1,
            'status' => $data['status'] ?? 'available',
            'facilities' => $data['facilities'] ?? null,
        ]);

        LogActivityHelper::addToLog('Menambah kamar: ' . $room->name, [
            'id' => $room->id,
            'name' => $room->name,
            'boarding_house_id' => $room->boarding_house_id,
        ]);

        return $room;
    }
}
