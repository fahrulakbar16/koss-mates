<?php

namespace App\Actions\Role;

use App\Models\Role;

class GetRoleStatsAction
{
    /**
     * Get role statistics (users and permissions).
     */
    public function execute(Role $role): array
    {
        $users = $role->users()
            ->select('id', 'name', 'email', 'username')
            ->orderBy('name')
            ->get();

        $permissions = $role->permissions()
            ->select('id', 'name', 'display_name', 'group_name')
            ->orderBy('group_name')
            ->orderBy('name')
            ->get();

        return [
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
            ],
            'users' => $users,
            'permissions' => $permissions,
        ];
    }
}

