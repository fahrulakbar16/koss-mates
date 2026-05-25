<?php

namespace App\Http\Controllers;

use App\Actions\User\DeleteUserAction;
use App\Actions\User\GetUsersAction;
use App\Actions\User\StoreUserAction;
use App\Actions\User\UpdateUserAction;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Helpers\LogActivityHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
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
        abort_unless(Gate::allows('users.view'), 403, 'Anda tidak memiliki akses untuk melihat data pengguna');

        $sortBy = $request->get('sortBy', 'created_at');
        $sortDirection = $request->get('sortDirection', 'desc');
        $search = $request->input('search');
        $role = $request->input('role');
        $status = $request->input('status');

        $result = app(GetUsersAction::class)->execute($request);
        $users = $result['users'];

        $roles = Role::all();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'roles' => $roles,
            'search' => $search,
            'role' => $role,
            'status' => $status,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_unless(Gate::allows('users.create'), 403, 'Anda tidak memiliki akses untuk menambah pengguna');

        $roles = Role::all();

        return Inertia::render('Admin/Users/Create', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        abort_unless(Gate::allows('users.create'), 403, 'Anda tidak memiliki akses untuk menambah pengguna');

        app(StoreUserAction::class)->execute($request->validated());

        LogActivityHelper::addToLog('Menambah pengguna: ' . $request->name, $request->validated());

        return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        abort_unless(Gate::allows('users.view'), 403, 'Anda tidak memiliki akses untuk melihat detail pengguna');

        $user->load('roles', 'permissions');

        return Inertia::render('Admin/Users/Show', [
            'user' => (new UserResource($user))->resolve(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        abort_unless(Gate::allows('users.edit'), 403, 'Anda tidak memiliki akses untuk mengubah pengguna');

        $user->load('roles');
        $roles = Role::all();

        return Inertia::render('Admin/Users/Edit', [
            'user' => (new UserResource($user))->resolve(),
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        abort_unless(Gate::allows('users.edit'), 403, 'Anda tidak memiliki akses untuk mengubah pengguna');

        app(UpdateUserAction::class)->execute($user, $request->validated());

        LogActivityHelper::addToLog('Mengubah pengguna: ' . $user->name, $request->validated());

        return redirect()->back()->with('success', 'Pengguna berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        abort_unless(Gate::allows('users.delete'), 403, 'Anda tidak memiliki akses untuk menghapus pengguna');

        try {
            $userName = $user->name;
            app(DeleteUserAction::class)->execute($user);

            LogActivityHelper::addToLog('Menghapus pengguna: ' . $userName);

            return redirect()->back()->with('success', 'Pengguna berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus pengguna: ' . $e->getMessage());
        }
    }
}
