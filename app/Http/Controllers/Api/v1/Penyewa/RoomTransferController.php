<?php

namespace App\Http\Controllers\Api\v1\Penyewa;

use App\Actions\Room\GetRoomByUser;
use App\Actions\RoomTransfer\CalculateRoomTransferCostAction;
use App\Actions\RoomTransfer\CreateRoomTransferAction;
use App\Actions\RoomTransfer\GetRoomTranfer;
use App\Http\Controllers\Controller;
use App\Http\Requests\CalculateRoomTransferCostRequest;
use App\Http\Requests\StoreRoomTransferRequest;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;

class RoomTransferController extends Controller
{

    use ApiResponse;

    /**
     * Mendapatkan daftar permintaan pindah kamar.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = Auth::user();

        $activeRoom = app(GetRoomByUser::class)->execute($user);

        if (!$activeRoom) {
            return $this->apiResponse(null, 'Anda tidak memiliki kamar aktif.');
        }

        $transfers = app(GetRoomTranfer::class)->execute($user, $activeRoom);

        return $this->apiResponse($transfers, 'Data berhasil diambil.');
    }

    /**
     * Menyimpan permintaan pindah kamar baru.
     *
     * @param  \App\Http\Requests\StoreRoomTransferRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRoomTransferRequest $request)
    {

        $user = Auth::user();
        $activeRoom = app(GetRoomByUser::class)->execute($user);

        if (!$activeRoom) {
            return $this->apiResponse(null, 'Anda tidak memiliki kamar aktif.');
        }
        //kondisi jika punya taguhan belum lunas tidak bisa pindah
        // if ($activeRoom->transa)

        $cost = app(CalculateRoomTransferCostAction::class)->execute($request->validated(), $activeRoom);

        app(CreateRoomTransferAction::class)->execute($request->validated() + $cost + ['user_room_id' => $activeRoom->id]);

        return $this->apiResponse(null, 'Permintaan pindah kamar berhasil dikirim.');
    }

    /**
     * Menghitung biaya pindah kamar.
     *
     * @param  \App\Http\Requests\CalculateRoomTransferCostRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateCost(CalculateRoomTransferCostRequest $request)
    {
        $user = Auth::user();
        $activeRoom = app(GetRoomByUser::class)->execute($user);

        if (!$activeRoom) {
            return $this->apiResponse(null, 'Anda tidak memiliki kamar aktif.');
        }

        $result = app(CalculateRoomTransferCostAction::class)->execute($request->validated(), $activeRoom);

        return $this->apiResponse($result, 'Biaya pindah kamar berhasil dihitung.');
    }
}
