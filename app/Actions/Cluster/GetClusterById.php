<?php

namespace App\Actions\Cluster;

use App\Models\Cluster;
use App\Models\User;
use Illuminate\Http\Request;

class GetClusterById
{
    /**
     * Get cursor paginated clusters with filters.
     */
    public function execute($id, $user)
    {
        $isPemilik = $user->hasRole('Pemilik');

        $cluster = Cluster::with(['boardingHouses' => function ($query) use ($isPemilik, $user) {
            $query->with('images', 'owner')
                ->when($isPemilik, function ($query) use ($user) {
                    $query->where('owner_id', $user->id);
                })
                ->withCount('rooms')
                ->orderBy('created_at', 'desc');
        }])->when($isPemilik, function ($query) use ($user) {
            $query->whereHas('boardingHouses', function ($query) use ($user) {
                $query->where('owner_id', $user->id);
            });
        })
            ->withCount([
                'rooms as total_rooms' => function ($query) use ($isPemilik, $user) {
                    $query->when($isPemilik, function ($query) use ($user) {
                        $query->whereHas('boardingHouse', function ($query) use ($user) {
                            $query->where('owner_id', $user->id);
                        });
                    });
                },
            ])
            ->withCount([
                'rooms as total_occupied_rooms' => function ($query) use ($isPemilik, $user) {
                    $query->when($isPemilik, function ($query) use ($user) {
                        $query->whereHas('boardingHouse', function ($query) use ($user) {
                            $query->where('owner_id', $user->id);
                        });
                    })->where('rooms.status', 'occupied');
                },
                'rooms as total_available_rooms' => function ($query) use ($isPemilik, $user) {
                    $query->when($isPemilik, function ($query) use ($user) {
                        $query->whereHas('boardingHouse', function ($query) use ($user) {
                            $query->where('owner_id', $user->id);
                        });
                    })->where('rooms.status', 'available');
                },
            ])
            ->findOrFail($id);

        return $cluster;
    }
}
