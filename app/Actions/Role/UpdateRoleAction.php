<?php

namespace App\Actions\Role;

use App\Models\Role;
use App\Helpers\LogActivityHelper;

class UpdateRoleAction
{
    /**
     * Update a role.
     */
    public function execute(Role $role, array $data): Role
    {
        $role->update([
            'name' => $data['name'],
            'leave_quota_per_year' => $data['leave_quota_per_year'] ?? ($role->leave_quota_per_year ?? 0),
            'loan_quota' => $data['loan_quota'] ?? ($role->loan_quota ?? 0),
        ]);

        LogActivityHelper::addToLog('Memperbarui jabatan: ' . $role->name, [
            'id' => $role->id,
            'name' => $role->name,
        ]);

        return $role->fresh();
    }
}
