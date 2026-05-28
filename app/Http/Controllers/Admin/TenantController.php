<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Tenant\CreateTenantAccount;
use App\Actions\Tenant\GetTenantsWithRooms;
use App\Actions\Tenant\UpdateTenantAccount;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTenantRequest;
use App\Http\Requests\UpdateTenantRequest;
use App\Http\Resources\Tenant\TenantResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TenantController extends Controller
{
    /**
     * Display a listing of tenants with rooms.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $tenants = app(GetTenantsWithRooms::class)->execute($request->all());

        return Inertia::render('Admin/Tenants/Index', [
            'tenants' => TenantResource::collection($tenants)->response()->getData(true),
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new tenant.
     */
    public function create()
    {
        return Inertia::render('Admin/Tenants/Create');
    }

    /**
     * Store a newly created tenant in storage.
     */
    public function store(StoreTenantRequest $request)
    {
        app(CreateTenantAccount::class)->execute($request->validated());

        return redirect()->route('admin.tenants.index')->with('success', 'Akun penyewa berhasil dibuat');
    }

    /**
     * Display the specified tenant.
     */
    public function show($id)
    {
        $viewer = Auth::user();
        $tenant = User::with([
            'tenant',
            'rooms' => function ($query) {
                $query->with(['room.boardingHouse', 'plan', 'transactions'])
                    ->orderBy('created_at', 'desc');
            },
            'transactions' => function ($query) {
                $query->whereIn('status', ['incomplete', 'pending'])
                    ->with(['room.boardingHouse', 'roomPrice'])
                    ->orderBy('created_at', 'desc');
            }
        ])->findOrFail($id);

        if ($viewer->hasRole('Pengelola')) {
            $belongsToViewer = $tenant->rooms()
                ->whereHas('room.boardingHouse', fn($q) => $q->where('pengelola_id', $viewer->id))
                ->exists();
            abort_unless($belongsToViewer, 403, 'Anda tidak memiliki akses ke data penyewa ini');
        } elseif ($viewer->hasRole('Pemilik')) {
            $belongsToViewer = $tenant->rooms()
                ->whereHas('room.boardingHouse', fn($q) => $q->where('owner_id', $viewer->id))
                ->exists();
            abort_unless($belongsToViewer, 403, 'Anda tidak memiliki akses ke data penyewa ini');
        }

        return Inertia::render('Admin/Tenants/Show', [
            'tenant' => [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'username' => $tenant->username,
                'email' => $tenant->email,
                'phone' => $tenant->tenant?->phone,
                'address' => $tenant->tenant?->address,
                'id_card_number' => $tenant->tenant?->id_card_number,
                'birth_date' => $tenant->tenant?->birth_date?->format('Y-m-d'),
                'gender' => $tenant->tenant?->gender,
                'emergency_contact' => $tenant->tenant?->emergency_contact,
                'is_moved' => (bool) $tenant->tenant?->is_moved,
                'created_at' => $tenant->created_at->format('d M Y'),
                'rooms' => $tenant->rooms->map(function ($room) {
                    return [
                        'id' => $room->id,
                        'room_id' => $room->room_id,
                        'boarding_house_id' => $room->boarding_house_id,
                        'room_name' => $room->room?->name,
                        'boarding_house_name' => $room->room?->boardingHouse?->name,
                        'plan_name' => $room->plan?->plan_name,
                        'price' => $room->plan?->price,
                        'status' => $room->status,
                        'check_in_date' => $room->tanggal_mulai,
                        'check_out_date' => $room->tanggal_berakhir,
                        'total_transactions' => $room->transactions->count(),
                    ];
                }),
                'pending_transactions' => $tenant->transactions->map(function ($transaction) {
                    return [
                        'id' => $transaction->id,
                        'transaction_code' => $transaction->transaction_code,
                        'room_name' => $transaction->room?->name,
                        'boarding_house_name' => $transaction->room?->boardingHouse?->name,
                        'plan_name' => $transaction->roomPrice?->name ?? 'Custom',
                        'total_price' => $transaction->total_price,
                        'payment_scheme' => $transaction->payment_scheme,
                        'status' => $transaction->status,
                        'created_at' => $transaction->created_at->format('d M Y'),
                    ];
                }),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified tenant.
     */
    public function edit($id)
    {
        $tenant = User::with('tenant')->findOrFail($id);

        return Inertia::render('Admin/Tenants/Edit', [
            'tenant' => [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'username' => $tenant->username,
                'email' => $tenant->email,
                'phone' => $tenant->tenant?->phone,
                'address' => $tenant->tenant?->address,
                'id_card_number' => $tenant->tenant?->id_card_number,
                'birth_date' => $tenant->tenant?->birth_date?->format('Y-m-d'),
                'gender' => $tenant->tenant?->gender,
                'emergency_contact' => $tenant->tenant?->emergency_contact,
            ],
        ]);
    }

    /**
     * Update the specified tenant in storage.
     */
    public function update(UpdateTenantRequest $request, $id)
    {
        $tenant = User::findOrFail($id);
        app(UpdateTenantAccount::class)->execute($tenant, $request->validated());

        return redirect()->route('admin.tenants.index')->with('success', 'Akun penyewa berhasil diperbarui');
    }

    /**
     * Remove the specified tenant from storage.
     */
    public function destroy($id)
    {
        $tenant = User::findOrFail($id);

        // Check if tenant has active rooms
        $hasActiveRooms = $tenant->rooms()->whereIn('status', ['booked', 'checked_in'])->exists();

        if ($hasActiveRooms) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus penyewa yang masih memiliki kamar aktif');
        }

        $tenant->tenant()->delete();
        $tenant->delete();

        return redirect()->route('admin.tenants.index')->with('success', 'Akun penyewa berhasil dihapus');
    }

    public function updateMoveStatus(Request $request, $id)
    {
        $request->validate([
            'is_moved' => 'required|boolean',
        ]);

        $tenant = User::findOrFail($id);

        $tenant->tenant()->update([
            'is_moved' => $request->is_moved,
        ]);

        // Log activity
        \App\Helpers\LogActivityHelper::addToLog('Memperbarui status pindah penyewa: ' . $tenant->name, [
            'id' => $tenant->id,
            'name' => $tenant->name,
            'is_moved' => $request->is_moved,
        ]);

        return redirect()->back()->with('success', 'Status pindah berhasil diperbarui');
    }
}
