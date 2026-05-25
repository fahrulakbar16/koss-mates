<?php

namespace App\Actions\BoardingHouse;

use App\Models\BoardingHouse;

class GetBoardingHousePaginate
{
    public function execute($data, $user = null)
    {
        $lat = $data['lat'] ?? null;
        $lng = $data['long'] ?? null;
        $isPemilik = $user ? $user->hasRole('Pemilik') : false;

        $query = BoardingHouse::with(['cluster', 'owner', 'images', 'rooms.prices'])
            ->withCount(['rooms','roomsAvailable'])
            ->when($isPemilik, function ($query) use ($user) {
                $query->where('owner_id', $user->id);
            })
            ->when($data['search'] ?? null, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($data['cluster_id'] ?? null, function ($query, $clusterId) {
                $query->where('cluster_id', $clusterId);
            })
            ->when($data['gender'] ?? null, function ($query, $gender) {
                $query->where('gender', $gender);
            })
            ->when($lat && $lng, function ($query) use ($lat, $lng) {
                // Calculate distance using Haversine formula (distance in kilometers)
                $query->selectRaw(
                    'boarding_houses.*, (6371 * acos(
                        cos(radians(?)) *
                        cos(radians(latitude)) *
                        cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) *
                        sin(radians(latitude))
                    )) AS distance',
                    [$lat, $lng, $lat]
                )
                    ->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->orderBy('distance', 'asc');
            }, function ($query) {
                // Default ordering when no lat/lng provided
                $query->orderBy('created_at', 'desc');
            });

        if (!empty($data['limit'])) {
            return $query->limit($data['limit'])->get();
        } else {
            if (!empty($data['paginate'])) {
                return $query->paginate($data['per_page'] ?? 10)->withQueryString();
            }
            return $query->cursorPaginate($data['per_page'] ?? 10)->withQueryString();
        }
    }
}
