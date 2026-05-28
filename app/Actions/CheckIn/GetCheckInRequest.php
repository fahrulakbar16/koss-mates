<?php

namespace App\Actions\CheckIn;

use App\Models\UserRooms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetCheckInRequest
{
    public function execute(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $user = Auth::user();

        $query = UserRooms::with(['user.tenant', 'room.boardingHouse'])
            ->where('status', 'checkin_open')
            ->whereNotNull('foto_kamar')
            ->where('verifikasi_admin', 0);

        if ($user->hasRole('Pengelola')) {
            $query->whereHas('room.boardingHouse', fn($q) => $q->where('pengelola_id', $user->id));
        } elseif ($user->hasRole('Pemilik')) {
            $query->whereHas('room.boardingHouse', fn($q) => $q->where('owner_id', $user->id));
        }

        if ($search) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        return $query->orderBy('updated_at', 'desc')->paginate($perPage);
    }
}
