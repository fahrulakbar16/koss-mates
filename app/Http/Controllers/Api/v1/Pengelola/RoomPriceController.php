<?php

namespace App\Http\Controllers\Api\v1\Pengelola;

use App\Actions\Room\DeleteRoomPriceAction;
use App\Actions\Room\StoreRoomPriceAction;
use App\Actions\Room\UpdateRoomPriceAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoomPrice\StoreRoomPriceRequest;
use App\Http\Requests\RoomPrice\UpdateRoomPriceRequest;
use App\Http\Resources\RoomPrice\RoomPriceResource;
use App\Models\Room;
use App\Models\RoomPrice;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class RoomPriceController extends Controller
{
    use ApiResponse;


    private function getRoom($clusterId, $boardingHouseId, $roomId)
    {
        return Room::whereHas('boardingHouse', function ($query) use ($clusterId, $boardingHouseId) {
            $query->where('cluster_id', $clusterId)->where('id', $boardingHouseId);
        })->findOrFail($roomId);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $clusterId, $boardingHouseId, $roomId)
    {
        $room = $this->getRoom($clusterId, $boardingHouseId, $roomId);
        $prices = $room->prices()->orderBy('duration')->get();

        return $this->apiResponse(RoomPriceResource::collection($prices), 'Data harga kamar berhasil diambil');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomPriceRequest $request, $clusterId, $boardingHouseId, $roomId)
    {
        $room = $this->getRoom($clusterId, $boardingHouseId, $roomId);

        $roomPrice = app(StoreRoomPriceAction::class)->execute($room, $request->validated());

        return $this->apiResponse(null, 'Harga kamar berhasil ditambahkan', 'success', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomPriceRequest $request, $clusterId, $boardingHouseId, $roomId, $id)
    {
        // Ensure room ownership
        $room = $this->getRoom($clusterId, $boardingHouseId, $roomId);

        $roomPrice = $room->prices()->findOrFail($id);

        $roomPrice = app(UpdateRoomPriceAction::class)->execute($roomPrice, $request->validated());

        return $this->apiResponse(null, 'Harga kamar berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($clusterId, $boardingHouseId, $roomId, $id)
    {
        // Ensure room ownership
        $room = $this->getRoom($clusterId, $boardingHouseId, $roomId);

        $roomPrice = $room->prices()->findOrFail($id);

        app(DeleteRoomPriceAction::class)->execute($roomPrice);

        return $this->apiResponse(null, 'Harga kamar berhasil dihapus');
    }
}
