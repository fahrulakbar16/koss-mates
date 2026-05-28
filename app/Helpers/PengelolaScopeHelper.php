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
     * Returns null for Superadmin (no filter needed — see all).
     */
    public static function getBoardingHouseIds(?User $user): ?array
    {
        if (!$user || $user->hasRole('Superadmin')) return null;

        if ($user->hasRole('Pemilik')) {
            return BoardingHouse::where('owner_id', $user->id)->pluck('id')->toArray();
        }

        if ($user->hasRole('Pengelola')) {
            return BoardingHouse::where('pengelola_id', $user->id)->pluck('id')->toArray();
        }

        return null;
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
