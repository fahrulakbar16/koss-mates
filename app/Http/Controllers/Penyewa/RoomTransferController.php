<?php

namespace App\Http\Controllers\Penyewa;

use App\Actions\Room\GetRoomByUser;
use App\Actions\RoomTransfer\CalculateRoomTransferCostAction;
use App\Actions\RoomTransfer\CreateRoomTransferAction;
use App\Actions\RoomTransfer\GetAvailableTargetRoomsAction;
use App\Actions\RoomTransfer\GetPendingRequest;
use App\Actions\RoomTransfer\GetRoomTranfer;
use App\Actions\RoomTransfer\GetRoomTransferFormResourcesAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CalculateRoomTransferCostRequest;
use App\Http\Requests\StoreRoomTransferRequest;
use App\Models\UserRooms;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RoomTransferController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $activeRoom = app(GetRoomByUser::class)->execute($user);

        if (!$activeRoom) {
            return redirect()->back()->with('error', 'Anda tidak memiliki kamar aktif.');
        }

        $transfers = app(GetRoomTranfer::class)->execute($user, $activeRoom);

        return Inertia::render('Penyewa/RoomTransfers/Index', [
            'transfers' => $transfers,
        ]);
    }

    public function create()
    {
        $user = Auth::user();

        $userRoomsId = UserRooms::where('user_id', $user->id)->where('status', '=', 'checked_in')->pluck('id');

        $pendingRequest = app(GetPendingRequest::class)->execute($userRoomsId);

        if ($pendingRequest) {

            if ($pendingRequest->status == 'pending') {
                return redirect()->route('penyewa.transfers.index')->with('error', 'Anda sudah memiliki permintaan pindah kamar yang belum disetujui.');
            }

            if ($pendingRequest->status == 'approved' && $pendingRequest->user_id == $user->id) {
                return redirect()->route('penyewa.transfers.index')->with('error', 'Anda sudah memiliki permintaan pindah kamar yang sudah disetujui.');
            }
        }

        $resources = app(GetRoomTransferFormResourcesAction::class)->execute($user);

        return Inertia::render('Penyewa/RoomTransfers/Create', [
            'userRooms' => $resources['userRooms'],
            'boardingHouses' => $resources['boardingHouses'],
        ]);
    }

    public function store(StoreRoomTransferRequest $request)
    {
        $user = Auth::user();
        $activeRoom = app(GetRoomByUser::class)->execute($user);

        if (!$activeRoom) {
            return redirect()->route('penyewa.transfers.index')->with('error', 'Anda tidak memiliki kamar aktif.');
        }

        $cost = app(CalculateRoomTransferCostAction::class)->execute($request->validated(), $activeRoom);

        app(CreateRoomTransferAction::class)->execute($request->validated() + $cost + ['user_room_id' => $activeRoom->id]);

        return redirect()->route('penyewa.transfers.index')->with('success', 'Permintaan pindah kamar berhasil dikirim.');
    }

    public function getAvailableRooms($userRoomId)
    {
        $rooms = app(GetAvailableTargetRoomsAction::class)->execute($userRoomId);

        return response()->json($rooms);
    }

    public function calculateCost(CalculateRoomTransferCostRequest $request)
    {
        $user = Auth::user();
        $activeRoom = app(GetRoomByUser::class)->execute($user);
        $result = app(CalculateRoomTransferCostAction::class)->execute($request->validated(), $activeRoom);

        return response()->json($result);
    }
}
