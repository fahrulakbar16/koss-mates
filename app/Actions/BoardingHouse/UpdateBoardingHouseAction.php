<?php

namespace App\Actions\BoardingHouse;

use App\Models\BoardingHouse;
use App\Models\User;
use App\Helpers\LogActivityHelper;
use Illuminate\Support\Facades\Storage;

class UpdateBoardingHouseAction
{
    public function execute(BoardingHouse $boardingHouse, array $data, ?User $user = null): BoardingHouse
    {
        $clusterName = 'Kos';
        $clusterId = $data['cluster_id'] ?? $boardingHouse->cluster_id;

        if (!empty($clusterId)) {
            $cluster = \App\Models\Cluster::find($clusterId);
            if ($cluster) {
                $clusterName = $cluster->name;
            }
        }

        $number = $data['number'] ?? $boardingHouse->number;
        $formattedNumber = str_pad($number, 2, '0', STR_PAD_LEFT);
        $name = $clusterName . '-' . $formattedNumber;

        $updateData = [
            'owner_id' => $data['owner_id'],
            'cluster_id' => $data['cluster_id'] ?? null,
            'number' => $number,
            'name' => $name,
            'description' => $data['description'] ?? null,
            'address' => $data['address'],
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'gender' => $data['gender'],
            'persentasi_pemilik' => $data['persentasi_pemilik'],
        ];

        if ($user && $user->hasRole('Superadmin') && array_key_exists('pengelola_id', $data)) {
            $updateData['pengelola_id'] = $data['pengelola_id'] ?: null;
        }

        // Handle thumbnail update
        if (!empty($data['thumbnail'])) {
            // Delete old thumbnail if exists
            if ($boardingHouse->thumbnail) {
                Storage::disk('public')->delete($boardingHouse->thumbnail);
            }
            $updateData['thumbnail'] = $data['thumbnail'];
        } elseif (!empty($data['remove_thumbnail']) && $data['remove_thumbnail']) {
            // Remove thumbnail if explicitly requested
            if ($boardingHouse->thumbnail) {
                Storage::disk('public')->delete($boardingHouse->thumbnail);
            }
            $updateData['thumbnail'] = null;
        }

        $boardingHouse->update($updateData);

        LogActivityHelper::addToLog('Memperbarui kos: ' . $boardingHouse->name, [
            'id' => $boardingHouse->id,
            'name' => $boardingHouse->name,
        ]);

        return $boardingHouse->fresh();
    }
}
