<?php

namespace App\Actions\Cluster;

use App\Models\Cluster;
use App\Models\User;
use App\Helpers\LogActivityHelper;

class StoreClusterAction
{
    /**
     * Store a new cluster.
     * If user is Pengelola, auto-assign pengelola_id.
     * If user is Superadmin and $data['pengelola_id'] is provided, use that.
     */
    public function execute(array $data, ?User $user = null): Cluster
    {
        $pengelolaId = null;
        if ($user) {
            if ($user->hasRole('Pengelola')) {
                $pengelolaId = $user->id;
            } elseif ($user->hasRole('Superadmin') && !empty($data['pengelola_id'])) {
                $pengelolaId = $data['pengelola_id'];
            }
        }

        $cluster = Cluster::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'address' => $data['address'] ?? null,
            'pengelola_id' => $pengelolaId,
        ]);

        LogActivityHelper::addToLog('Menambah kluster: ' . $cluster->name, [
            'id' => $cluster->id,
            'name' => $cluster->name,
        ]);

        return $cluster;
    }
}
