<?php

namespace App\Actions\BoardingHouse;

use App\Models\BoardingHouse;
use App\Helpers\LogActivityHelper;

class StoreBoardingHouseAction
{
    /**
     * Store a new boarding house.
     */
    public function execute(array $data): BoardingHouse
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

        $boardingHouse = BoardingHouse::create([
            'owner_id' => $data['owner_id'],
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
