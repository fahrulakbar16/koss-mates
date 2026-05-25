<?php

namespace App\Actions\BoardingHouse;

use App\Models\BoardingHouse;

class GetBoardingHouseWithRoomUser
{
    /**
     * Get boarding house by ID with all required relations.
     */
    public function execute(int $id): BoardingHouse
    {
        $boardingHouse = BoardingHouse::with(['cluster', 'owner', 'images', 'rooms.prices'])
            ->findOrFail($id);

        return $boardingHouse;
    }
}
