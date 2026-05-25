<?php

namespace App\Http\Controllers;

use App\Actions\Room\BatchStoreRoomAction;
use App\Actions\Room\DeleteRoomAction;
use App\Actions\Room\GetRoomsAction;
use App\Actions\Room\StoreRoomAction;
use App\Actions\Room\StoreRoomPricesAction;
use App\Actions\Room\UpdateRoomAction;
use App\Http\Requests\Room\BatchStoreRoomRequest;
use App\Http\Requests\Room\StoreRoomRequest;
use App\Http\Requests\Room\UpdateRoomRequest;
use App\Models\BoardingHouse;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\UserRooms;
use App\Helpers\LogActivityHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class RoomController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth:sanctum',
        ];
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request, BoardingHouse $boardingHouse)
    {
        abort_unless(Gate::allows('rooms.create'), 403, 'Anda tidak memiliki akses untuk menambah kamar');

        $data = $request->validated();
        $data['boarding_house_id'] = $boardingHouse->id;

        app(StoreRoomAction::class)->execute($data);

        LogActivityHelper::addToLog('Menambah kamar: ' . $request->number . ' di ' . $boardingHouse->name, $data);

        return redirect()->back()->with('success', 'Kamar berhasil ditambahkan');
    }

    /**
     * Store multiple newly created resources in storage.
     */
    public function batchStore(BatchStoreRoomRequest $request, BoardingHouse $boardingHouse)
    {
        abort_unless(Gate::allows('rooms.create'), 403, 'Anda tidak memiliki akses untuk menambah kamar');

        app(BatchStoreRoomAction::class)->execute($boardingHouse, $request->validated());

        LogActivityHelper::addToLog('Menambah batch kamar di ' . $boardingHouse->name, $request->validated());

        return redirect()->back()->with('success', 'Batch kamar berhasil ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomRequest $request, BoardingHouse $boardingHouse, Room $room)
    {
        abort_unless(Gate::allows('rooms.edit'), 403, 'Anda tidak memiliki akses untuk mengubah kamar');

        app(UpdateRoomAction::class)->execute($room, $request->validated());

        LogActivityHelper::addToLog('Mengubah kamar: ' . $room->name . ' di ' . $boardingHouse->name, $request->validated());

        return redirect()->back()->with('success', 'Kamar berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BoardingHouse $boardingHouse, Room $room)
    {
        abort_unless(Gate::allows('rooms.delete'), 403, 'Anda tidak memiliki akses untuk menghapus kamar');

        try {
            $roomName = $room->name;
            app(DeleteRoomAction::class)->execute($room);

            LogActivityHelper::addToLog('Menghapus kamar: ' . $roomName . ' dari ' . $boardingHouse->name);

            return redirect()->back()->with('success', 'Kamar berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus kamar: ' . $e->getMessage());
        }
    }

    /**
     * Show the page to edit room prices.
     */
    public function editPrices(BoardingHouse $boardingHouse, Room $room)
    {
        abort_unless(Gate::allows('rooms.edit'), 403, 'Anda tidak memiliki akses untuk mengatur harga kamar');

        // Verify room belongs to boarding house
        if ($room->boarding_house_id !== $boardingHouse->id) {
            abort(403, 'Kamar tidak terkait dengan kos ini');
        }

        $room->load(['boardingHouse', 'prices']);

        return Inertia::render('Admin/Rooms/Prices', [
            'boardingHouse' => $boardingHouse,
            'room' => $room,
        ]);
    }

    /**
     * Store or update room prices based on duration.
     */
    public function storePrices(Request $request, BoardingHouse $boardingHouse, Room $room)
    {
        abort_unless(Gate::allows('rooms.edit'), 403, 'Anda tidak memiliki akses untuk mengatur harga kamar');

        $validator = Validator::make($request->all(), [
            'prices' => ['required', 'array'],
            'prices.*.duration' => ['required', 'integer', 'min:1'],
            'prices.*.price' => ['required', 'numeric', 'min:0'],
        ], [
            'prices.required' => 'Data harga wajib diisi.',
            'prices.*.duration.required' => 'Durasi wajib diisi.',
            'prices.*.duration.integer' => 'Durasi harus berupa angka bulat.',
            'prices.*.duration.min' => 'Durasi minimal 1 bulan.',
            'prices.*.price.required' => 'Harga wajib diisi.',
            'prices.*.price.numeric' => 'Harga harus berupa angka.',
            'prices.*.price.min' => 'Harga minimal 0.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        app(StoreRoomPricesAction::class)->execute($room, $request->input('prices', []));

        LogActivityHelper::addToLog('Mengatur harga kamar: ' . $room->name . ' di ' . $boardingHouse->name, $request->input('prices', []));

        return redirect()->back()->with('success', 'Harga kamar berhasil diperbarui');
    }

    /**
     * Show room detail with expenses.
     */
    public function show(Request $request, BoardingHouse $boardingHouse, Room $room)
    {
        // abort_unless(Gate::allows('rooms.view'), 403, 'Anda tidak memiliki akses untuk melihat detail kamar');

        // Verify room belongs to boarding house
        if ($room->boarding_house_id !== $boardingHouse->id) {
            abort(403, 'Kamar tidak terkait dengan kos ini');
        }

        $room = Room::with([
            'prices',
            'boardingHouse'
        ])->findOrFail($room->id);

        return Inertia::render('Admin/Rooms/Show', [
            'boardingHouse' => $boardingHouse,
            'room' => $room,
        ]);
    }

    /**
     * Cancel room booking.
     */
    public function cancelBooking(BoardingHouse $boardingHouse, Room $room)
    {
        try {
            app(\App\Actions\Room\CancelBookingAction::class)->execute($boardingHouse, $room);

            return redirect()->back()->with('success', 'Booking berhasil dibatalkan dan kamar sekarang tersedia');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Check out tenant from room.
     */
    public function checkout(BoardingHouse $boardingHouse, Room $room)
    {
        try {
            app(\App\Actions\Room\CheckoutAction::class)->execute($boardingHouse, $room);

            return redirect()->back()->with('success', 'Penyewa berhasil check-out dan kamar sekarang tersedia');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat check-out: ' . $e->getMessage());
        }
    }
}
