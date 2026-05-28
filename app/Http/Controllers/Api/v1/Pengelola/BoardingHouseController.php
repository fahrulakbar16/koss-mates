<?php

namespace App\Http\Controllers\Api\v1\Pengelola;

use App\Actions\BoardingHouse\DeleteBoardingHouseAction;
use App\Actions\BoardingHouse\GetBoardingHouseById;
use App\Actions\BoardingHouse\GetBoardingHousePaginate;
use App\Actions\BoardingHouse\StoreBoardingHouseAction;
use App\Actions\BoardingHouse\UpdateBoardingHouseAction;
use App\Actions\BoardingHouse\UpdateBoardingHouseActionApi;
use App\Http\Controllers\Controller;
use App\Http\Requests\BoardingHouse\StoreBoardingHouseRequest;
use App\Http\Requests\BoardingHouse\UpdateBoardingHouseRequest;
use App\Http\Resources\BoardingHouse\BoardingHouseDetailResource;
use App\Http\Resources\BoardingHouse\BoardingHouseResource;
use App\Models\BoardingHouse;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Log;

class BoardingHouseController extends Controller
{
    use ApiResponse;


    /**
     * Menampilkan daftar boarding house.
     */
    public function index($clusterId)
    {
        $request = request()->merge(['cluster_id' => $clusterId]);
        $boardingHouses = app(GetBoardingHousePaginate::class)->execute($request->all(), auth()->user());
        return $this->apiResponse(BoardingHouseResource::collection($boardingHouses)->response()->getData(true), 'Data boarding house berhasil diambil');
    }

    /**
     * Menampilkan detail boarding house.
     */
    public function show($clusterId, $id)
    {
        $boardingHouse = app(GetBoardingHouseById::class)->execute($id);
        return $this->apiResponse(new BoardingHouseDetailResource($boardingHouse), 'Data boarding house berhasil diambil');
    }

    /**
     * Menyimpan boarding house baru ke database.
     */
    public function store(StoreBoardingHouseRequest $request, $clusterId)
    {
        $data = $request->validated();
        $data['cluster_id'] = $clusterId;

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('boarding-houses', 'public');
        }

        Log::info('Data boarding house', $data);
        app(StoreBoardingHouseAction::class)->execute($data, auth()->user());

        return $this->apiResponse(null, 'Boarding house berhasil ditambahkan');
    }

    /**
     * Memperbarui data boarding house di database.
     */
    public function update(UpdateBoardingHouseRequest $request, $clusterId, $id)
    {
        $boardingHouse = BoardingHouse::where('cluster_id', $clusterId)->findOrFail($id);
        app(UpdateBoardingHouseActionApi::class)->execute($boardingHouse, $request->validated());

        return $this->apiResponse(null, 'Boarding house berhasil diperbarui');
    }

    /**
     * Menghapus boarding house dari database.
     */
    public function destroy($clusterId, $id)
    {
        $boardingHouse = BoardingHouse::where('cluster_id', $clusterId)->findOrFail($id);
        app(DeleteBoardingHouseAction::class)->execute($boardingHouse);

        return $this->apiResponse(null, 'Boarding house berhasil dihapus');
    }
}
