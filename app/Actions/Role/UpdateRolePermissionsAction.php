<?php

namespace App\Actions\Role;

use App\Models\Role;
use App\Helpers\LogActivityHelper;
use Spatie\Permission\Models\Permission;

class UpdateRolePermissionsAction
{
    /**
     * Update role permissions.
     */
    public function execute(Role $role, array $permissionIds, bool $checked): void
    {
        $permissions = Permission::whereIn('id', $permissionIds)->get();

        if ($checked) {
            $role->givePermissionTo($permissions);
        } else {
            foreach ($permissions as $permission) {
                $role->revokePermissionTo($permission);
            }
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissionNames = $permissions->pluck('name')->toArray();
        $action = $checked ? 'Memberikan' : 'Mencabut';
        LogActivityHelper::addToLog($action . ' izin pada jabatan ' . $role->name . ': ' . implode(', ', $permissionNames), [
            'role_id' => $role->id,
            'role_name' => $role->name,
            'permissions' => $permissionNames,
            'checked' => $checked,
        ]);
    }
}
