<?php

namespace App\Http\Controllers\Penyewa;

use App\Actions\Room\GetRoomByUser;
use App\Actions\Room\SubmitCheckin;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitCheckinRequest;
use App\Http\Resources\Room\RoomActiveResource;
use App\Helpers\LogActivityHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class RoomController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // dd($user->toArray());

        $activeRoom = app(GetRoomByUser::class)->execute($user);

        Log::info('Active room: ' . $activeRoom);

        if ($activeRoom) {
            $transaction = $user->transactions()->where([
                'room_id' => $activeRoom->room_id,
                'room_price_id' => $activeRoom->room_price_id,
                'type' => 'checkin_open'
            ])->first();


            if ($transaction && $transaction->status != "completed") {
                $activeRoom = null;
            }
        }

        // If status is booked, check the check-in flow
        if ($activeRoom && $activeRoom->status == "checkin_open") {
            // If no photo uploaded yet, redirect to check-in form
            if (empty($activeRoom->foto_kamar)) {
                return Inertia::render('Penyewa/Rooms/Checkin', [
                    'activeRoom' => new RoomActiveResource($activeRoom)
                ]);
            }

            // If photo uploaded but not verified by admin yet, show waiting page
            if (!$activeRoom->verifikasi_admin) {
                return Inertia::render('Penyewa/Rooms/WaitingVerification', [
                    'activeRoom' => new RoomActiveResource($activeRoom)
                ]);
            }
        }

        return Inertia::render('Penyewa/Rooms/Show', [
            'activeRoom' => $activeRoom ? new RoomActiveResource($activeRoom) : null
        ]);
    }

    public function submitCheckin(SubmitCheckinRequest $request)
    {
        try {
            // Execute the check-in action
            app(SubmitCheckin::class)->execute(Auth::user(), $request->file('foto_kamar'));

            LogActivityHelper::addToLog('Submit check-in kamar');

            // Redirect back to room index which will show waiting verification page
            return redirect()->route('penyewa.rooms.index')
                ->with('success', 'Foto kamar berhasil diupload. Menunggu verifikasi admin.');
        } catch (\Exception $e) {
            return redirect()->route('penyewa.rooms.index')
                ->with('error', $e->getMessage());
        }
    }
}
