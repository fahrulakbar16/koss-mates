<?php

namespace App\Actions\Tenant;

use App\Models\User;

class GetTenantsWithRooms
{
    public function execute(array $data)
    {
        $user = auth()->user();

        // Base query
        $query = User::role('Penyewa')
            ->whereHas('rooms', function ($query) {
                $query->whereIn('status', ['booked', 'checked_in']);
            })
            ->with([
                'tenant',
                'rooms' => function ($query) {
                    $query->with(['room.boardingHouse', 'plan'])
                        ->whereIn('status', ['booked', 'checked_in'])
                        ->latest();
                }
            ])
            ->withCount(['transactions as pending_transactions_count' => function ($query) {
                $query->whereIn('status', ['pending', 'incomplete']);
            }]);

        if (!empty($data['search'])) {
            $query->where(function($q) use ($data) {
                $q->where('name', 'like', "%{$data['search']}%")
                  ->orWhere('email', 'like', "%{$data['search']}%")
                  ->orWhere('username', 'like', "%{$data['search']}%");
            });
        }

        // Filter for Pemilik
        if ($user && $user->hasRole('Pemilik')) {
            $query->whereHas('rooms.room.boardingHouse', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            });
        }

        return $query->orderBy('name')
            ->paginate(!empty($data['per_page']) ? $data['per_page'] : 10)
            ->withQueryString();
    }
}
