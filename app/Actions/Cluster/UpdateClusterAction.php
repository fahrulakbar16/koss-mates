<?php

namespace App\Actions\Cluster;

use App\Models\Cluster;
use App\Helpers\LogActivityHelper;

class UpdateClusterAction
{
    /**
     * Update a cluster.
     */
    public function execute(Cluster $cluster, array $data): Cluster
    {
        $cluster->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'address' => $data['address'] ?? null,
        ]);

        LogActivityHelper::addToLog('Memperbarui kluster: ' . $cluster->name, [
            'id' => $cluster->id,
            'name' => $cluster->name,
        ]);

        return $cluster->fresh();
    }
}
