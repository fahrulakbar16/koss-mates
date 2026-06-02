<?php

namespace App\Actions\Cluster;

use App\Models\Cluster;
use App\Models\User;
use App\Helpers\LogActivityHelper;

class UpdateClusterAction
{
    public function execute(Cluster $cluster, array $data, ?User $user = null): Cluster
    {
        $updateData = [
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'address' => $data['address'] ?? null,
        ];

        if ($user && $user->hasRole('Superadmin') && array_key_exists('pengelola_id', $data)) {
            $updateData['pengelola_id'] = $data['pengelola_id'] ?: null;
        }

        $cluster->update($updateData);

        LogActivityHelper::addToLog('Memperbarui kluster: ' . $cluster->name, [
            'id' => $cluster->id,
            'name' => $cluster->name,
        ]);

        return $cluster->fresh();
    }
}
