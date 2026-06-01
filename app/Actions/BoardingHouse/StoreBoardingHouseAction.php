<?php

namespace App\Actions\BoardingHouse;

use App\Models\BoardingHouse;
use App\Models\User;
use App\Helpers\LogActivityHelper;

class StoreBoardingHouseAction
{
    /**
     * Store a new boarding house.
     * If user is Pengelola, auto-assign pengelola_id.
     * If user is Superadmin and $data['pengelola_id'] is provided, use that.
     */
    public function execute(array $data, ?User $user = null): BoardingHouse
    {
        $clusterName = 'Kos';
        if (!empty($data['cluster_id'])) {
            $cluster = \App\Models\Cluster::find($data['cluster_id']);
            if ($cluster) {
                $clusterName = $cluster->name;
            }
        }

        $number = $data['number'];
        $formattedNumber = str_pad($number, 2, '0', STR_PAD_LEFT);
        $name = $clusterName . '-' . $formattedNumber;

        $pengelolaId = null;
        if ($user) {
            if ($user->hasRole('Pengelola')) {
                $pengelolaId = $user->id;
            } elseif ($user->hasRole('Superadmin') && !empty($data['pengelola_id'])) {
                $pengelolaId = $data['pengelola_id'];
            }
        }

        $boardingHouse = BoardingHouse::create([
            'owner_id' => $data['owner_id'],
            'pengelola_id' => $pengelolaId,
            'cluster_id' => $data['cluster_id'] ?? null,
            'thumbnail' => $data['thumbnail'] ?? null,
            'number' => $number,
            'name' => $name,
            'description' => $data['description'] ?? null,
            'address' => $data['address'],
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'gender' => $data['gender'],
            'persentasi_pemilik' => $data['persentasi_pemilik'],
        ]);

        LogActivityHelper::addToLog('Menambah kos: ' . $boardingHouse->name, [
            'id' => $boardingHouse->id,
            'name' => $boardingHouse->name,
        ]);

        return $boardingHouse;
    }
}
