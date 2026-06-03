<?php

namespace App\Helpers;

use App\Models\BoardingHouse;
use App\Models\User;

class PengelolaScopeHelper
{
    /**
     * Apply scope directly on a boarding_houses query.
     * - Superadmin: no filter
     * - Pemilik: where owner_id = user->id
     * - Pengelola: where pengelola_id = user->id
     */
    public static function applyBoardingHouseScope($query, ?User $user): void
    {
        if (!$user || $user->hasRole('Superadmin')) return;

        if ($user->hasRole('Pemilik')) {
            $query->where('owner_id', $user->id);
        } elseif ($user->hasRole('Pengelola')) {
            $query->where('pengelola_id', $user->id);
        }
    }

    /**
     * Get boarding house IDs accessible to a user.
     * Returns null for Superadmin with no cluster filter (no restriction needed).
     * Pass $clusterId to also restrict to a specific cluster.
     */
    public static function getBoardingHouseIds(?User $user, ?int $clusterId = null): ?array
    {
        $isSuperadmin = !$user || $user->hasRole('Superadmin');

        if ($isSuperadmin && !$clusterId) return null;

        $query = BoardingHouse::query();

        if (!$isSuperadmin) {
            if ($user->hasRole('Pemilik')) {
                $query->where('owner_id', $user->id);
            } elseif ($user->hasRole('Pengelola')) {
                $query->where('pengelola_id', $user->id);
            } else {
                return null;
            }
        }

        if ($clusterId) {
            $query->where('cluster_id', $clusterId);
        }

        return $query->pluck('id')->toArray();
    }

    /**
     * Apply a whereIn filter on a query using accessible boarding house IDs.
     * Column defaults to 'boarding_house_id'.
     */
    public static function applyBoardingHouseIdFilter($query, ?User $user, string $column = 'boarding_house_id'): void
    {
        $ids = self::getBoardingHouseIds($user);
        if ($ids !== null) {
            $query->whereIn($column, $ids);
        }
    }
}
