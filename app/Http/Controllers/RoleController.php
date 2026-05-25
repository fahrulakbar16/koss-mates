<?php

namespace App\Http\Controllers;

use App\Actions\Role\DeleteRoleAction;
use App\Actions\Role\GetRoleStatsAction;
use App\Actions\Role\GetRolesAction;
use App\Actions\Role\StoreRoleAction;
use App\Actions\Role\UpdateRoleAction;
use App\Actions\Role\UpdateRolePermissionsAction;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRolePermissionsRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Helpers\LogActivityHelper;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth:sanctum',
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_unless(Gate::allows('roles.view'), 403, 'Anda tidak memiliki akses untuk melihat data jabatan');

        $sortBy = $request->get('sortBy', 'created_at');
        $sortDirection = $request->get('sortDirection', 'desc');
        $search = $request->input('search');

        $roles = app(GetRolesAction::class)->execute($request);

        return Inertia::render('Admin/Roles/Index', [
            'roles' => $roles,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_unless(Gate::allows('roles.create'), 403, 'Anda tidak memiliki akses untuk menambah jabatan');

        // Method ini tidak digunakan, tapi tetap dilindungi
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        abort_unless(Gate::allows('roles.create'), 403, 'Anda tidak memiliki akses untuk menambah jabatan');

        app(StoreRoleAction::class)->execute($request->validated());

        LogActivityHelper::addToLog('Menambah jabatan: ' . $request->name, $request->validated());

        return redirect()->back()->with('success', 'Peran berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort_unless(Gate::allows('roles.view'), 403, 'Anda tidak memiliki akses untuk melihat detail jabatan');

        // Method ini tidak digunakan, tapi tetap dilindungi
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        abort_unless(Gate::allows('roles.edit'), 403, 'Anda tidak memiliki akses untuk mengubah jabatan');

        $permissions = Permission::all()->groupBy('group_name');

        return Inertia::render('Admin/Roles/Edit', [
            'role' => (new RoleResource($role->load('permissions')))->resolve(),
            'permissions' => $permissions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        abort_unless(Gate::allows('roles.edit'), 403, 'Anda tidak memiliki akses untuk mengubah jabatan');

        // Branch by intent: if permission_ids present, handle permission toggle; else update role fields
        if ($request->has('permission_ids')) {
            $validated = $request->validate([
                'permission_ids' => 'required|array',
                'permission_ids.*' => 'integer|exists:permissions,id',
                'checked' => 'required|boolean',
            ]);

            app(UpdateRolePermissionsAction::class)->execute(
                $role,
                $validated['permission_ids'],
                $validated['checked']
            );

            return back()->with('success', 'Hak akses berhasil diperbarui');
        }

        // Otherwise update basic fields (name + quotas)
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'leave_quota_per_year' => ['nullable', 'integer', 'min:0'],
            'loan_quota' => ['nullable', 'numeric', 'min:0'],
        ]);

        app(UpdateRoleAction::class)->execute($role, $data);

        LogActivityHelper::addToLog('Mengubah jabatan: ' . $role->name, $data);

        return back()->with('success', 'Jabatan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        abort_unless(Gate::allows('roles.delete'), 403, 'Anda tidak memiliki akses untuk menghapus jabatan');

        try {
            $roleName = $role->name;
            app(DeleteRoleAction::class)->execute($role);

            LogActivityHelper::addToLog('Menghapus jabatan: ' . $roleName);

            return redirect()->back()->with('success', 'Peran berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus peran: ' . $e->getMessage());
        }
    }

    /**
     * Return users and permissions for a Role (for modal stats)
     */
    public function stats(Role $role)
    {
        abort_unless(Gate::allows('roles.view'), 403, 'Anda tidak memiliki akses untuk melihat statistik jabatan');

        $stats = app(GetRoleStatsAction::class)->execute($role);

        return response()->json($stats);
    }
}
