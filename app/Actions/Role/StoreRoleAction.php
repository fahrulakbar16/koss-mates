<?php

namespace App\Actions\Role;

use App\Models\Role;
use App\Helpers\LogActivityHelper;

class StoreRoleAction
{
    /**
     * Store a new role.
     */
    public function execute(array $data): Role
    {
        $payload = [
            'name' => $data['name'],
            'leave_quota_per_year' => $data['leave_quota_per_year'] ?? 0,
            'loan_quota' => $data['loan_quota'] ?? 0,
        ];

        $role = Role::create($payload);

        LogActivityHelper::addToLog('Menambah jabatan: ' . $role->name, [
            'id' => $role->id,
            'name' => $role->name,
        ]);

        return $role;
    }
}
