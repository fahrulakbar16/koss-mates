<?php

namespace App\Actions\CheckIn;

use App\Models\UserRooms;
use Illuminate\Http\Request;

class GetCheckInRequest
{
    public function execute(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $query = UserRooms::with(['user.tenant', 'room.boardingHouse'])
            ->where('status', 'checkin_open')
            ->whereNotNull('foto_kamar')
            ->where('verifikasi_admin', 0);

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('updated_at', 'desc')->paginate($perPage);
    }
}
