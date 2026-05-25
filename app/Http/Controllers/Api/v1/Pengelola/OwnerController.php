<?php

namespace App\Http\Controllers\Api\v1\Pengelola;

use App\Actions\Admin\CreateOwner;
use App\Actions\Admin\UpdateOwner;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOwnerRequest;
use App\Http\Requests\Admin\UpdateOwnerRequest;
use App\Http\Resources\Owner\OwnerDetailResource;
use App\Http\Resources\Owner\OwnerResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class OwnerController extends Controller
{
    use ApiResponse;

    /**
     * Menampilkan daftar pemilik.
     */
    public function index(): JsonResponse
    {
        $owners = User::role('Pemilik')
            ->with(['owner'])
            ->withCount('boardingHouses')
            ->latest()
            ->get();

        return $this->apiResponse(
            OwnerResource::collection($owners),
            'Data pemilik berhasil diambil'
        );
    }

    /**
     * Menyimpan akun pemilik baru.
     */
    public function store(StoreOwnerRequest $request): JsonResponse
    {
        $owner = app(CreateOwner::class)->execute($request->validated());

        return $this->apiResponse(
            new OwnerResource($owner),
            'Akun pemilik berhasil dibuat',
            201
        );
    }

    /**
     * Menampilkan detail pemilik.
     */
    public function show(int $id): JsonResponse
    {
        $owner = User::with(['owner', 'boardingHouses.rooms'])->withCount('boardingHouses')->findOrFail($id);

        if (!$owner->hasRole('Pemilik')) {
            return throw new CustomException('User bukan pemilik', 404);
        }

        return $this->apiResponse(
            new OwnerDetailResource($owner),
            'Detail pemilik berhasil diambil'
        );
    }

    /**
     * Memperbarui akun pemilik.
     */
    public function update(UpdateOwnerRequest $request, int $id): JsonResponse
    {
        $owner = User::findOrFail($id);

        if (!$owner->hasRole('Pemilik')) {
            return throw new CustomException('User bukan pemilik', 404);
        }

        $updatedOwner = app(UpdateOwner::class)->execute($owner, $request->validated());

        return $this->apiResponse(
            new OwnerResource($updatedOwner),
            'Akun pemilik berhasil diperbarui'
        );
    }

    /**
     * Menghapus akun pemilik.
     */
    public function destroy(int $id): JsonResponse
    {
        $owner = User::findOrFail($id);

        if (!$owner->hasRole('Pemilik')) {
            return throw new CustomException('User bukan pemilik', 404);
        }

        if ($owner->boardingHouses()->exists()) {
            return throw new CustomException('Tidak dapat menghapus pemilik yang memiliki properti boarding house', 400);
        }

        $owner->owner()->delete();
        $owner->delete();

        return $this->apiResponse(
            null,
            'Akun pemilik berhasil dihapus'
        );
    }
}
