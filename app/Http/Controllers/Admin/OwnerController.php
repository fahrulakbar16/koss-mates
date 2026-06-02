<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoardingHouse;
use App\Models\User;
use App\Models\Owner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Requests\Admin\StoreOwnerRequest;
use App\Http\Requests\Admin\UpdateOwnerRequest;
use App\Actions\Admin\CreateOwner;
use App\Actions\Admin\UpdateOwner;

class OwnerController extends Controller
{
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = User::role('Pemilik')->with(['owner']);

        if ($user->hasRole('Pengelola')) {
            $ownerIds = BoardingHouse::where('pengelola_id', $user->id)
                ->whereNotNull('owner_id')
                ->distinct()
                ->pluck('owner_id');
            $query->whereIn('id', $ownerIds);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $owners = $query->latest()
            ->paginate(!empty($request->per_page) ? $request->per_page : 10)
            ->withQueryString();

        return Inertia::render('Admin/Owners/Index', [
            'owners' => $owners,
            'search' => $request->search,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Owners/Create');
    }

    public function store(StoreOwnerRequest $request)
    {
        app(CreateOwner::class)->execute($request->validated());

        return redirect()->route('admin.owners.index')->with('success', 'Akun pemilik berhasil dibuat');
    }

    public function show($id)
    {
        $owner = User::with(['owner', 'boardingHouses.rooms'])->findOrFail($id);

        return Inertia::render('Admin/Owners/Show', [
            'owner' => $owner,
        ]);
    }

    public function edit($id)
    {
        $owner = User::with(['owner'])->findOrFail($id);

        return Inertia::render('Admin/Owners/Edit', [
            'owner' => $owner,
        ]);
    }

    public function update(UpdateOwnerRequest $request, $id)
    {
        $owner = User::findOrFail($id);

        app(UpdateOwner::class)->execute($owner, $request->validated());

        return redirect()->route('admin.owners.index')->with('success', 'Akun pemilik berhasil diperbarui');
    }

    public function destroy($id)
    {
        $owner = User::findOrFail($id);

        if ($owner->boardingHouses()->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus pemilik yang memiliki properti boarding house');
        }

        $owner->owner()->delete();
        $owner->delete();

        return redirect()->route('admin.owners.index')->with('success', 'Akun pemilik berhasil dihapus');
    }
}
