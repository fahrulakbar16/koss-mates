<?php

namespace App\Http\Controllers\Api\v1;

use App\Actions\Room\GetRoomByUser;
use App\Actions\Room\SubmitCheckin;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitCheckinRequest;
use App\Http\Resources\Room\RoomActiveResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckinController extends Controller
{

    /**
     * Submit foto kamar untuk check-in.
     *
     * **Authorization:** Bearer Token.
     *
     * @param SubmitCheckinRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitCheckin(SubmitCheckinRequest $request)
    {
        try {
            // Execute the check-in action
            app(SubmitCheckin::class)->execute(Auth::user(), $request->file('foto_kamar'));

            return response()->json([
                'message' => 'Foto kamar berhasil diupload. Menunggu verifikasi admin.'
            ], 201);
        } catch (\Exception $e) {
            throw new CustomException($e->getMessage(), 400);
        }
    }

    public function getStatus()
    {
        try {
            $activeRoom = Auth::user()->rooms()->where('status', 'checkin_open')->first();

            if (!$activeRoom) {
                return response()->json([
                    'message' => 'Anda tidak memiliki kamar aktif',
                ], 404);
            }
            Log::info($activeRoom);

            $message = '';
            if ($activeRoom->status == 'checkin_open' && empty($activeRoom->foto_kamar)) {
                $message = 'Anda harus melakukan check-in terlebih dahulu dengan mengupload foto kamar';
            } elseif ($activeRoom->status == 'checkin_open' && $activeRoom->foto_kamar && !$activeRoom->verifikasi_admin) {
                $message = 'Foto kamar belum diverifikasi admin';
            } elseif ($activeRoom->status == 'checked_in' && $activeRoom->foto_kamar && $activeRoom->verifikasi_admin) {
                $message = 'Anda sudah check-in';
            }

            return response()->json([
                'message' => $message,
                'data' => [
                    'status' => $activeRoom->verifikasi_admin,
                    'foto_kamar' => $activeRoom->foto_kamar,
                ]
            ], 200);
        } catch (\Exception $e) {
            throw new CustomException($e->getMessage(), 400);
        }
    }
}
