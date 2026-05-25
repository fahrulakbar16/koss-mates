<?php

namespace App\Actions\BoardingHouse;

use App\Models\BoardingHouse;
use App\Helpers\LogActivityHelper;
use Illuminate\Support\Facades\Storage;

class DeleteBoardingHouseAction
{
    /**
     * Delete a boarding house.
     *
     * @throws \Exception
     */
    public function execute(BoardingHouse $boardingHouse): void
    {
        // Delete thumbnail if exists
        if ($boardingHouse->thumbnail) {
            Storage::disk('public')->delete($boardingHouse->thumbnail);
        }

        $boardingHouseName = $boardingHouse->name;
        $boardingHouseId = $boardingHouse->id;
        $boardingHouse->delete();

        LogActivityHelper::addToLog('Menghapus kos: ' . $boardingHouseName, [
            'id' => $boardingHouseId,
            'name' => $boardingHouseName,
        ]);
    }
}
