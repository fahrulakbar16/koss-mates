<?php

namespace App\Http\Controllers\Api\v1;

use App\Actions\BoardingHouse\GetBoardingHouseById;
use App\Actions\BoardingHouse\GetBoardingHousePaginate;
use App\Http\Controllers\Controller;
use App\Http\Requests\BoardingHouser\GetListRequest;
use App\Http\Resources\BoardingHouse\BoardingHouseDetailResource;
use App\Http\Resources\BoardingHouse\BoardingHouseResource;
use Illuminate\Http\Request;

class BoardingHouseController extends Controller
{
    use \App\Traits\ApiResponse;

    /**
     * Menampilkan daftar seluruh kos.
     */
    public function index(GetListRequest $request)
    {
        // Mengambil data boarding house beserta paginasi menggunakan action GetBoardingHousePaginate
        $boardingHouses = app(GetBoardingHousePaginate::class)->execute($request->all(), auth()->user());

        $data = BoardingHouseResource::collection($boardingHouses)->response()->getData(true);

        return $this->apiResponse($data, 'Data kos berhasil diambil');
    }

    /**
     * Menampilkan detail kos berdasarkan ID.
     */
    public function show(string $id)
    {
        // Mengambil data boarding house berdasarkan ID menggunakan action GetBoardingHouseById
        $boardingHouse = app(GetBoardingHouseById::class)->execute($id);

        // Mengembalikan response JSON berisi detail boarding house
        return $this->apiResponse(new BoardingHouseDetailResource($boardingHouse), 'Detail kos berhasil diambil');
    }
}
