<?php

namespace App\Http\Controllers\Api\v1;

use App\Actions\Room\GetRoomByUser;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Room\RoomActiveResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoomActiveController extends Controller
{

    use AuthorizesRequests;
    use \App\Traits\ApiResponse;

    /**
     * Mendapatkan data room active user.
     *
     * **Authorization:** Bearer Token.
     * **Policy:** User harus sudah melakukan check-in (upload foto kamar)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $activeRoom = app(GetRoomByUser::class)->execute($user);


            Log::info('Active room', [
                'activeRoom' => $activeRoom,
            ]);

            if (!$activeRoom) {
                throw new CustomException('Tidak ada kamar aktif', 404);
            }


            if ($activeRoom && $activeRoom->status == "checkin_open") {
                throw new CustomException('Harap lakukan check-in', 404);
            }

            // Check policy using Gate
            $this->authorize('accessRoomActive', $activeRoom);

            return $this->apiResponse(new RoomActiveResource($activeRoom), 'Data room active berhasil diambil');
        } catch (\Exception $e) {
            throw new CustomException($e->getMessage(), 400);
        }
    }
}
