<?php

namespace App\Actions\Cluster;

use App\Models\Cluster;
use App\Helpers\LogActivityHelper;

class StoreClusterAction
{
    /**
     * Store a new cluster.
     */
    public function execute(array $data): Cluster
    {
        $cluster = Cluster::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'address' => $data['address'] ?? null,
        ]);

        LogActivityHelper::addToLog('Menambah kluster: ' . $cluster->name, [
            'id' => $cluster->id,
            'name' => $cluster->name,
        ]);

        return $cluster;
    }
}
