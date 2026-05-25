<?php

namespace App\Actions\DamageReport;

use App\Models\DamageReport;
use App\Models\User;
use App\Models\UserRooms;
use App\Helpers\LogActivityHelper;
use Illuminate\Http\UploadedFile;

class CreateDamageReport
{
    /**
     * Create a new damage report.
     *
     * @param User $user
     * @param array $data
     * @param UploadedFile|null $photo
     * @return DamageReport
     * @throws \Exception
     */
    public function execute(User $user, array $data, ?UploadedFile $photo = null): DamageReport
    {
        // Get the user's active room
        $activeRoom = UserRooms::where('user_id', $user->id)
            ->whereIn('status', ['booked', 'checked_in'])
            ->latest()
            ->first();

        if (!$activeRoom) {
            throw new \Exception('Anda tidak memiliki kamar aktif');
        }

        // Handle photo upload if provided
        $photoPath = null;
        if ($photo) {
            $filename = time() . '_' . $user->id . '_' . $photo->getClientOriginalName();
            $photoPath = $photo->storeAs('damage-reports', $filename, 'public');
        }

        // Create damage report
        $damageReport = DamageReport::create([
            'user_room_id' => $activeRoom->id,
            'user_id' => $user->id,
            'title' => $data['title'],
            'description' => $data['description'],
            'photo' => $photoPath,
            'status' => 'pending',
        ]);

        LogActivityHelper::addToLog('Membuat laporan kerusakan: ' . $damageReport->title, [
            'id' => $damageReport->id,
            'title' => $damageReport->title,
        ]);

        return $damageReport;
    }
}
