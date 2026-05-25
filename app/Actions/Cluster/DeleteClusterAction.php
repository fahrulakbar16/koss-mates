<?php

namespace App\Actions\Cluster;

use App\Models\Cluster;
use App\Helpers\LogActivityHelper;

class DeleteClusterAction
{
    /**
     * Delete a cluster.
     *
     * @throws \Exception
     */
    public function execute(Cluster $cluster): void
    {
        // Check if cluster has boarding houses
        // if ($cluster->boardingHouses()->count() > 0) {
        //     throw new \Exception('Tidak dapat menghapus cluster yang masih memiliki boarding house');
        // }

        $clusterName = $cluster->name;
        $clusterId = $cluster->id;
        $cluster->delete();

        LogActivityHelper::addToLog('Menghapus kluster: ' . $clusterName, [
            'id' => $clusterId,
            'name' => $clusterName,
        ]);
    }
}
