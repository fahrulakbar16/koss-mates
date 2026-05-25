<?php

namespace App\Http\Controllers\Api\v1\Pengelola;

use App\Actions\Tenant\CreateTenantAccount;
use App\Actions\Tenant\GetTenantsWithRooms;
use App\Actions\Tenant\UpdateTenantAccount;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTenantRequest;
use App\Http\Requests\UpdateTenantRequest;
use App\Http\Resources\Tenant\TenantResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class TenantController extends Controller
{
    use ApiResponse;

    /**
     * Menampilkan daftar penyewa yang memiliki kamar aktif.
     */
    public function index(Request $request): JsonResponse
    {
        $tenants = app(GetTenantsWithRooms::class)->execute($request->all());

        return $this->apiResponse(
            TenantResource::collection($tenants),
            'Data penyewa berhasil diambil'
        );
    }

    /**
     * Menyimpan akun penyewa baru.
     */
    public function store(StoreTenantRequest $request): JsonResponse
    {
        $tenant = app(CreateTenantAccount::class)->execute($request->validated());

        return $this->apiResponse(
            null,
            'Akun penyewa berhasil dibuat',
            201
        );
    }

    /**
     * Menampilkan detail penyewa.
     */
    public function show(int $id): JsonResponse
    {
        $tenant = User::with([
            'tenant',
            'rooms.room.boardingHouse',
            'rooms.plan',
            'rooms.transactions'
        ])->findOrFail($id);

        // Cek apakah user memiliki role Penyewa
        if (!$tenant->hasRole('Penyewa')) {
            return throw new CustomException('User bukan penyewa', 404);
        }

        $data = [
            'id' => $tenant->id,
            'name' => $tenant->name,
            'username' => $tenant->username,
            'email' => $tenant->email,
            'phone' => $tenant->tenant?->phone,
            'gender' => $tenant->tenant?->gender,
            'id_card_number' => $tenant->tenant?->id_card_number,
            'birth_date' => $tenant->tenant?->birth_date,
            'address' => $tenant->tenant?->address,
            'emergency_contact' => $tenant->tenant?->emergency_contact,
            'room_booked' => $tenant->rooms->where('status', 'booked')->map(function ($room) {
                return [
                    'id' => $room->room?->id,
                    'room_name' => $room->room?->name,
                    'cluster_id' => $room->room?->boardingHouse?->cluster_id,
                    'boarding_house_id' => $room->room?->boardingHouse?->id,
                    'boarding_house_name' => $room->room?->boardingHouse?->name,
                    'plan_name' => $room->plan?->plan_name,
                    'price' => $room->plan?->price,
                    'status' => $room->status,
                    'check_in_date' => $room->check_in_date,
                    'check_out_date' => $room->check_out_date,
                    'total_transactions' => $room->transactions->count(),
                ];
            }),
            'rooms' => $tenant->rooms->where('status', '!=', 'booked')->map(function ($room) {
                return [
                    'id' => $room->id,
                    'room_name' => $room->room?->name,
                    'cluster_id' => $room->room?->boardingHouse?->cluster_id,
                    'boarding_house_id' => $room->room?->boardingHouse?->id,
                    'boarding_house_name' => $room->room?->boardingHouse?->name,
                    'plan_name' => $room->plan?->plan_name,
                    'price' => $room->plan?->price,
                    'status' => $room->status,
                    'check_in_date' => $room->check_in_date,
                    'check_out_date' => $room->check_out_date,
                    'total_transactions' => $room->transactions->count(),
                ];
            }),
            'pending_transactions' => $tenant->transactions->whereIn('status', ['pending', 'incomplete'])->map(function ($transaction) use ($tenant) {
                $statusText = $transaction->status === 'incomplete' ? 'Belum Lunas' : 'Belum Dibayar';
                $amount = 'Rp ' . number_format($transaction->total_price, 0, ',', '.');
                $boardingHouseName = $transaction->room?->boardingHouse?->name;

                return [
                    'id' => $transaction->id,
                    'transaction_code' => $transaction->transaction_code,
                    'room_name' => $transaction->room?->name,
                    'boarding_house_name' => $boardingHouseName,
                    'plan_name' => $transaction->roomPrice?->name ?? 'Custom',
                    'total_price' => $transaction->total_price,
                    'payment_scheme' => $transaction->payment_scheme,
                    'status' => $transaction->status,
                    'message' => "Halo {$tenant->name}, ini pengingat dari {$boardingHouseName} untuk tagihan sejumlah *{$amount}* dengan kode *{$transaction->transaction_code}* yang masih berstatus *{$statusText}*. Mohon segera diselesaikan. Terima kasih.",
                    'created_at' => $transaction->created_at->format('d M Y'),
                ];
            }),
        ];

        return $this->apiResponse($data, 'Detail penyewa berhasil diambil');
    }

    /**
     * Memperbarui akun penyewa.
     */
    public function update(UpdateTenantRequest $request, int $id): JsonResponse
    {
        $tenant = User::findOrFail($id);

        // Cek apakah user memiliki role Penyewa
        if (!$tenant->hasRole('Penyewa')) {
            return throw new CustomException('User bukan penyewa', 404);
        }

        $updatedTenant = app(UpdateTenantAccount::class)->execute($tenant, $request->validated());

        return $this->apiResponse(
            new TenantResource($updatedTenant),
            'Akun penyewa berhasil diperbarui'
        );
    }

    /**
     * Menghapus akun penyewa.
     */
    public function destroy(int $id): JsonResponse
    {
        $tenant = User::findOrFail($id);

        // Cek apakah user memiliki role Penyewa
        if (!$tenant->hasRole('Penyewa')) {
            return throw new CustomException('User bukan penyewa', 404);
        }

        // Cek apakah penyewa memiliki kamar aktif
        $hasActiveRooms = $tenant->rooms()
            ->whereIn('status', ['booked', 'checked_in'])
            ->exists();

        if ($hasActiveRooms) {
            return throw new CustomException('Tidak dapat menghapus penyewa yang masih memiliki kamar aktif', 400);
        }

        // Hapus detail penyewa terlebih dahulu
        $tenant->tenant()->delete();

        // Hapus user
        $tenant->delete();

        return $this->apiResponse(
            null,
            'Akun penyewa berhasil dihapus'
        );
    }
}
