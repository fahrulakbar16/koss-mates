<?php

namespace App\Actions\Room;

use App\Models\BoardingHouse;
use App\Models\Room;
use App\Models\UserRooms;
use App\Helpers\LogActivityHelper;
use Illuminate\Support\Facades\DB;

class CheckoutAction
{
    /**
     * Check out a tenant from a room.
     */
    public function execute(BoardingHouse $boardingHouse, Room $room): void
    {
        if ($room->status !== Room::STATUS_OCCUPIED) {
            throw new \Exception('Kamar tidak dalam status terisi (occupied)');
        }

        DB::transaction(function () use ($boardingHouse, $room) {
            // Find the active check-in record
            $userRoom = UserRooms::where('room_id', $room->id)
                ->where('status', 'checked_in')
                ->latest()
                ->first();

            if ($userRoom) {
                $userRoom->update([
                    'status' => 'checked_out',
                    'end_date' => now(),
                ]);
            }

            // Set room back to available
            $room->update(['status' => Room::STATUS_AVAILABLE]);

            LogActivityHelper::addToLog('Check-out penyewa dari kamar: ' . $room->name . ' di ' . $boardingHouse->name, [
                'room_id' => $room->id,
                'user_room_id' => $userRoom ? $userRoom->id : null,
                'boarding_house_id' => $boardingHouse->id,
            ]);
        });
    }
}
