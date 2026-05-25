<?php

namespace App\Http\Controllers\Api\v1\Pengelola;

use App\Actions\RoomTransfer\ProcessRoomTransferAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoomTransferActionRequest;
use App\Http\Resources\RoomTransferResource;
use App\Models\RoomTransfer;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class RoomTransferController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of room transfers.
     */
    public function index(Request $request)
    {
        $query = RoomTransfer::with([
            'userRoom.user',
            'userRoom.room', // Old room
            'room.boardingHouse' // New room
        ]);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('userRoom.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $transfers = $query->latest()->cursorPaginate(10)->withQueryString();

        return $this->apiResponse(RoomTransferResource::collection($transfers)->response()->getData(true), 'Data Berhasil Diambil');
    }

    /**
     * Display the specified room transfer.
     */
    public function show($id)
    {
        $transfer = RoomTransfer::with([
            'userRoom.user',
            'userRoom.room', // Old room
            'room.boardingHouse', // New room
        ])->findOrFail($id);

        return $this->apiResponse(new RoomTransferResource($transfer), 'Data Berhasil Diambil');
    }

    /**
     * Process a room transfer action (approve/reject).
     */
    public function action(RoomTransferActionRequest $request, $id)
    {
        $transfer = RoomTransfer::findOrFail($id);

        if ($transfer->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengajuan ini sudah diproses.',
            ], 422);
        }

        app(ProcessRoomTransferAction::class)->execute($transfer, $request->action);

        return $this->apiResponse(new RoomTransferResource($transfer->refresh()), 'Status pengajuan berhasil diperbarui.');
    }
}
