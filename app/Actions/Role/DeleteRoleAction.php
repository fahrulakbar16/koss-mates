<?php

namespace App\Actions\Role;

use App\Models\Role;
use App\Helpers\LogActivityHelper;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteRoleAction
{
    /**
     * Delete a role.
     *
     * @throws \Exception
     */
    public function execute(Role $role): void
    {
        if ($role->users()->exists()) {
            throw new \Exception('Peran memiliki pengguna.');
        }

        $roleName = $role->name;
        $roleId = $role->id;
        $role->delete();

        LogActivityHelper::addToLog('Menghapus jabatan: ' . $roleName, [
            'id' => $roleId,
            'name' => $roleName,
        ]);
    }
}
