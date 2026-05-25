<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoomTransferActionRequest;
use App\Http\Resources\RoomTransferResource;
use App\Models\RoomTransfer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoomTransferController extends Controller
{
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

        $transfers = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/RoomTransfers/Index', [
            'transfers' => RoomTransferResource::collection($transfers),
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function show(RoomTransfer $transfer)
    {
        $transfer->load([
            'userRoom.user',
            'userRoom.room', // Old room
            'room.boardingHouse',
            'userRoom.boardingHouse', // New room
        ]);
        return Inertia::render('Admin/RoomTransfers/Show', [
            'transfer' => new RoomTransferResource($transfer),
        ]);
    }

    public function action(RoomTransferActionRequest $request, RoomTransfer $transfer)
    {
        if ($transfer->status !== 'pending') {
            return back()->withErrors(['message' => 'Pengajuan ini sudah diproses.']);
        }

        app(\App\Actions\RoomTransfer\ProcessRoomTransferAction::class)->execute($transfer, $request->action);

        return back()->with('success', 'Status pengajuan berhasil diperbarui.');
    }
}
