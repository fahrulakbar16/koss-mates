<?php

namespace App\Actions\Room;

use App\Models\UserRooms;
use App\Models\User;
use App\Helpers\LogActivityHelper;
use Illuminate\Http\UploadedFile;

class SubmitCheckin
{
    /**
     * Submit check-in with photo upload.
     *
     * @param User $user
     * @param UploadedFile $photo
     * @return UserRooms
     * @throws \Exception
     */
    public function execute(User $user, UploadedFile $photo): UserRooms
    {
        // Get the active room
        $activeRoom = app(GetRoomByUser::class)->execute($user);

        // if (!$activeRoom || $activeRoom->status != 'cancelled') {
        //     throw new \Exception('Kamar tidak ditemukan atau status tidak valid');
        // }

        // Store the uploaded file
        $filename = time() . '_' . $user->id . '_' . $photo->getClientOriginalName();
        $path = $photo->storeAs('checkin-photos', $filename, 'public');

        // Update the user room with the photo path
        $activeRoom->update([
            'foto_kamar' => $path,
            'verifikasi_admin' => false, // Set to false, waiting for admin verification
        ]);

        LogActivityHelper::addToLog('Mengirim foto check-in: ' . $user->name, [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'room_id' => $activeRoom->room_id,
        ]);

        return $activeRoom;
    }
}
