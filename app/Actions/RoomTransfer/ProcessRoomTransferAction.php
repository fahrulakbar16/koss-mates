<?php

namespace App\Actions\RoomTransfer;

use App\Models\Room;
use App\Models\RoomTransfer;
use App\Models\UserRooms;
use App\Models\RekapHistory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProcessRoomTransferAction
{
    /**
     * Process a room transfer request (approve or reject).
     *
     * @param RoomTransfer $transfer
     * @param string $actionType Action type: 'approve' or 'reject'
     * @return void
     */
    public function execute(RoomTransfer $transfer, string $actionType): void
    {
        DB::transaction(function () use ($actionType, $transfer) {
            if ($actionType === 'approve') {
                $oldUserRoom = $transfer->userRoom;
                $newRoom = Room::findOrFail($transfer->room_id);
                $transferDate = Carbon::parse($transfer->plan_date);

                // 1. Create New UserRooms
                $newUserRoom = UserRooms::create([
                    'user_id' => $oldUserRoom->user_id,
                    'boarding_house_id' => $newRoom->boarding_house_id,
                    'room_id' => $newRoom->id,
                    'room_price_id' => $oldUserRoom->room_price_id,
                    'status' => 'booked',
                    'start_date' => $transferDate,
                ]);

                // 2. Move Payments (RekapHistory)
                // Logic: If stay in old room < 20 days, move ALL history. Otherwise move only FUTURE history.
                $stayDurationInDays = Carbon::parse($oldUserRoom->start_date)->diffInDays($transferDate);

                if ($stayDurationInDays < 20) {
                    $historiesToMove = RekapHistory::where('user_room_id', $oldUserRoom->id)->get();
                } else {
                    // Future payments: records where (year > transferYear) OR (year == transferYear AND month > transferMonth)
                    $historiesToMove = RekapHistory::where('user_room_id', $oldUserRoom->id)
                        ->where(function ($query) use ($transferDate) {
                            $query->where('year', '>', $transferDate->year)
                                ->orWhere(function ($q) use ($transferDate) {
                                    $q->where('year', $transferDate->year)
                                        ->where('month', '>', $transferDate->month);
                                });
                        })
                        ->get();
                }

                foreach ($historiesToMove as $history) {
                    $history->update(['user_room_id' => $newUserRoom->id]);
                }

                // 3. Update Old UserRooms
                $oldUserRoom->update([
                    'end_date' => $transferDate,
                ]);

                // Update transfer status
                $transfer->update([
                    'status' => 'approved',
                ]);
            } else {
                // Reject
                $transfer->update([
                    'status' => 'rejected',
                ]);
            }
        });
    }
}
