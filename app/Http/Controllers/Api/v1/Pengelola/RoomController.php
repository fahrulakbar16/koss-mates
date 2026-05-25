<?php

namespace App\Http\Controllers\Api\v1\Pengelola;

use App\Actions\Room\BatchStoreRoomAction;
use App\Actions\Room\CancelBookingAction;
use App\Actions\Room\CheckoutAction;
use App\Actions\Room\DeleteRoomAction;
use App\Actions\Room\GetRoomsAction;
use App\Actions\Room\StoreRoomAction;
use App\Actions\Room\UpdateRoomAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Room\BatchStoreRoomRequest;
use App\Http\Requests\Room\StoreRoomRequest;
use App\Http\Requests\Room\UpdateRoomRequest;
use App\Http\Resources\Room\RoomResource;
use App\Models\BoardingHouse;
use App\Models\Room;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    use ApiResponse;


    /**
     * Menampilkan daftar kamar.
     */
    public function index(Request $request, $clusterId, $boardingHouseId)
    {
        $boardingHouse = BoardingHouse::where('cluster_id', $clusterId)->findOrFail($boardingHouseId);
        $rooms = app(GetRoomsAction::class)->execute($request, $boardingHouse);

        return $this->apiResponse(RoomResource::collection($rooms['rooms'])->response()->getData(true), 'Data kamar berhasil diambil');
    }

    /**
     * Menyimpan kamar baru ke database.
     */
    public function store(StoreRoomRequest $request, $clusterId, $boardingHouseId)
    {
        // Ensure boarding house exists in the cluster
        BoardingHouse::where('cluster_id', $clusterId)->findOrFail($boardingHouseId);

        $data = $request->validated();
        $data['boarding_house_id'] = $boardingHouseId;
        app(StoreRoomAction::class)->execute($data);

        return $this->apiResponse(null, 'Kamar berhasil ditambahkan');
    }

    /**
     * Menyimpan kamar secara batch ke database.
     */
    public function batchStore(BatchStoreRoomRequest $request, $clusterId, $boardingHouseId)
    {
        $boardingHouse = BoardingHouse::where('cluster_id', $clusterId)->findOrFail($boardingHouseId);

        app(BatchStoreRoomAction::class)->execute($boardingHouse, $request->validated());

        return $this->apiResponse(null, 'Batch kamar berhasil ditambahkan');
    }

    /**
     * Memperbarui data kamar di database.
     */
    public function update(UpdateRoomRequest $request, $clusterId, $boardingHouseId, $id)
    {
        // Ensure availability of boarding house and cluster relation (optional but good for consistency)
        // Since we updating Room by ID directly, we could just find Room.
        // But to strictly follow route hierarchy:
        $room = Room::whereHas('boardingHouse', function ($query) use ($clusterId, $boardingHouseId) {
            $query->where('cluster_id', $clusterId)->where('id', $boardingHouseId);
        })->findOrFail($id);

        app(UpdateRoomAction::class)->execute($room, $request->validated());

        return $this->apiResponse(null, 'Kamar berhasil diperbarui');
    }

    /**
     * Menghapus kamar dari database.
     */
    public function destroy($clusterId, $boardingHouseId, $id)
    {
        $room = Room::whereHas('boardingHouse', function ($query) use ($clusterId, $boardingHouseId) {
            $query->where('cluster_id', $clusterId)->where('id', $boardingHouseId);
        })->findOrFail($id);

        app(DeleteRoomAction::class)->execute($room);

        return $this->apiResponse(null, 'Kamar berhasil dihapus');
    }

    /**
     * Membatalkan booking kamar.
     */
    public function cancelBooking($clusterId, $boardingHouseId, $id)
    {
        $boardingHouse = BoardingHouse::where('cluster_id', $clusterId)->findOrFail($boardingHouseId);
        $room = Room::where('boarding_house_id', $boardingHouse->id)->findOrFail($id);

        try {
            app(CancelBookingAction::class)->execute($boardingHouse, $room);

            return $this->apiResponse(null, 'Booking berhasil dibatalkan');
        } catch (\Exception $e) {
            return $this->apiResponse(null, $e->getMessage(), 'error', 400);
        }
    }

    /**
     * Check out penyewa dari kamar.
     */
    public function checkout($clusterId, $boardingHouseId, $id)
    {
        $boardingHouse = BoardingHouse::where('cluster_id', $clusterId)->findOrFail($boardingHouseId);
        $room = Room::where('boarding_house_id', $boardingHouse->id)->findOrFail($id);

        try {
            app(CheckoutAction::class)->execute($boardingHouse, $room);

            return $this->apiResponse(null, 'Penyewa berhasil check-out');
        } catch (\Exception $e) {
            return $this->apiResponse(null, $e->getMessage(), 'error', 400);
        }
    }
}
