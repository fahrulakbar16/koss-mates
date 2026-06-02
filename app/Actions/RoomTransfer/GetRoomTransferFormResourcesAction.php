<?php

namespace App\Actions\RoomTransfer;

use App\Models\BoardingHouse;
use App\Models\UserRooms;
use Illuminate\Support\Facades\Auth;

class GetRoomTransferFormResourcesAction
{
    /**
     * Get resources needed for the room transfer form.
     *
     * @param  \App\Models\User  $user
     * @return array
     */
    public function execute($user): array
    {
        $userRooms = UserRooms::with(['room', 'boardingHouse', 'plan'])
            ->where('user_id', $user->id)
            ->where('status', 'checked_in')
            ->get()
            ->map(function ($userRoom) {
                $sisaPembayaran = 0;
                if ($userRoom->plan && $userRoom->end_date > now()) {
                    $remainingMonths = now()->floatDiffInMonths($userRoom->end_date, false);
                    if ($remainingMonths > 0) {
                        $pricePerMonth = $userRoom->plan->price / $userRoom->plan->duration;
                        $sisaPembayaran = floor($remainingMonths) * $pricePerMonth;
                    }
                }
                $userRoom->sisa_pembayaran = $sisaPembayaran;
                return $userRoom;
            });

        $boardingHouseIds = $userRooms->pluck('boarding_house_id')->filter()->unique()->values()->toArray();

        $boardingHouses = BoardingHouse::whereIn('id', $boardingHouseIds)
            ->with(['rooms' => function ($query) {
                $query->where('status', 'available')->with('prices');
            }])->get();

        return [
            'userRooms' => $userRooms,
            'boardingHouses' => $boardingHouses,
        ];
    }
}
