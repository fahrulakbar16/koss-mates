<?php

namespace App\Actions\Tenant;

use App\Helpers\PengelolaScopeHelper;
use App\Models\User;

class GetTenantsWithRooms
{
    public function execute(array $data)
    {
        $user      = auth()->user();
        $clusterId = !empty($data['cluster_id']) ? (int) $data['cluster_id'] : null;

        // Resolve allowed boarding house IDs (null = no restriction for Superadmin)
        $boardingHouseIds = PengelolaScopeHelper::getBoardingHouseIds($user, $clusterId);

        $query = User::role('Penyewa')
            ->whereHas('rooms', function ($q) use ($boardingHouseIds) {
                $q->whereIn('status', ['booked', 'checked_in']);
                if ($boardingHouseIds !== null) {
                    $q->whereIn('boarding_house_id', $boardingHouseIds);
                }
            })
            ->with([
                'tenant',
                'rooms' => function ($q) use ($boardingHouseIds) {
                    $q->with(['room.boardingHouse', 'plan'])
                        ->whereIn('status', ['booked', 'checked_in'])
                        ->when($boardingHouseIds !== null, fn($q2) => $q2->whereIn('boarding_house_id', $boardingHouseIds))
                        ->latest();
                },
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

        return $query->orderBy('name')
            ->paginate(!empty($data['per_page']) ? $data['per_page'] : 10)
            ->withQueryString();
    }
}
