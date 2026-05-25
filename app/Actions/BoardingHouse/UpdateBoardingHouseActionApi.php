<?php

namespace App\Actions\BoardingHouse;

use App\Models\BoardingHouse;
use App\Models\Cluster;
use App\Helpers\LogActivityHelper;
use Illuminate\Support\Facades\Storage;

class UpdateBoardingHouseActionApi
{
    /**
     * Update a boarding house.
     */
    public function execute(BoardingHouse $boardingHouse, array $data): BoardingHouse
    {
        $clusterName = 'Kos';
        $clusterId = $data['cluster_id'] ?? $boardingHouse->cluster_id;

        if (!empty($clusterId)) {
            $cluster = Cluster::find($clusterId);
            if ($cluster) {
                $clusterName = $cluster->name;
            }
        }

        $number = $data['number'] ?? $boardingHouse->number;
        $formattedNumber = str_pad($number, 2, '0', STR_PAD_LEFT);
        $name = $clusterName . '-' . $formattedNumber;

        $updateData = [
            'owner_id' => $data['owner_id'] ?? $boardingHouse->owner_id,
            'cluster_id' => $data['cluster_id'] ?? $boardingHouse->cluster_id,
            'number' => $number,
            'name' => $name,
            'description' => $data['description'] ?? null,
            'address' => $data['address'],
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'gender' => $data['gender'],
            'persentasi_pemilik' => $data['persentasi_pemilik'] ?? $boardingHouse->persentasi_pemilik,
        ];

        // Handle thumbnail update
        if (!empty($data['thumbnail'])) {
            // Delete old thumbnail if exists
            if ($boardingHouse->thumbnail) {
                Storage::disk('public')->delete($boardingHouse->thumbnail);
            }

            $updateData['thumbnail'] = Storage::disk('public')->put('boarding-houses', $data['thumbnail']);
        }

        $boardingHouse->update($updateData);

        LogActivityHelper::addToLog('Memperbarui kos: ' . $boardingHouse->name, [
            'id' => $boardingHouse->id,
            'name' => $boardingHouse->name,
        ]);

        return $boardingHouse->fresh();
    }
}
