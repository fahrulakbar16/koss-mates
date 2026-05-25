<?php

namespace App\Actions\BoardingHouse;

use App\Models\BoardingHouse;

class GetBoardingHouseById
{
    /**
     * Get boarding house by ID with all required relations.
     */
    public function execute(int $id): BoardingHouse
    {
        $boardingHouse = BoardingHouse::with(['cluster', 'owner', 'images', 'rooms.prices'])
            ->withCount(['rooms as total_rooms', 'rooms as occupied_rooms' => function ($query) {
                $query->where('status', 'occupied');
            }])
            ->findOrFail($id);

        return $boardingHouse;
    }
}
