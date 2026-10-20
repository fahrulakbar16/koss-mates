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
        DB::transaction(function () use ($boardingHouse, $room) {
            $room = Room::whereKey($room->id)->lockForUpdate()->firstOrFail();

            // Find the active check-in record
            $userRoom = UserRooms::where('room_id', $room->id)
                ->whereIn('status', ['checked_in', 'checkin_open'])
                ->latest()
                ->lockForUpdate()
                ->first();

            // The room detail also treats an active tenancy as occupied,
            // even when the stored room status is out of sync.
            if (!$userRoom && $room->status !== Room::STATUS_OCCUPIED) {
                throw new \Exception('Kamar tidak dalam status terisi (occupied)');
            }

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
