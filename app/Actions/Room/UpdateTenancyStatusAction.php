<?php

namespace App\Actions\Room;

use App\Helpers\LogActivityHelper;
use App\Models\Room;
use App\Models\UserRooms;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UpdateTenancyStatusAction
{
    public const EDITABLE_STATUSES = ['booked', 'checkin_open', 'checked_in'];

    public const STATUSES = ['booked', 'checkin_open', 'checked_in', 'checked_out', 'cancelled'];

    public function execute(Room $room, int $tenancyId, string $status, string $previousStatus): void
    {
        if (!in_array($status, self::STATUSES, true)) {
            throw ValidationException::withMessages(['status' => 'Status sewa tidak valid.']);
        }

        DB::transaction(function () use ($room, $tenancyId, $status, $previousStatus) {
            $room = Room::whereKey($room->id)->lockForUpdate()->firstOrFail();
            $tenancy = UserRooms::where('room_id', $room->id)
                ->where('boarding_house_id', $room->boarding_house_id)
                ->whereKey($tenancyId)->lockForUpdate()->firstOrFail();

            if ($tenancy->status !== $previousStatus) {
                throw ValidationException::withMessages(['status' => 'Status sudah berubah. Muat ulang halaman sebelum menyimpan.']);
            }

            if (!in_array($tenancy->status, self::EDITABLE_STATUSES, true)) {
                throw ValidationException::withMessages([
                    'status' => 'Hanya sewa yang masih booking atau terisi yang dapat diubah.',
                ]);
            }

            $tenancy->update(['status' => $status]);
            $statuses = UserRooms::where('room_id', $room->id)->pluck('status');
            $roomStatus = $statuses->intersect(['checked_in', 'checkin_open'])->isNotEmpty()
                ? Room::STATUS_OCCUPIED
                : ($statuses->contains('booked') ? Room::STATUS_BOOKED
                    : ($room->status === Room::STATUS_MAINTENANCE ? Room::STATUS_MAINTENANCE : Room::STATUS_AVAILABLE));
            $room->update(['status' => $roomStatus]);

            LogActivityHelper::addToLog('Memperbarui status sewa kamar: ' . $room->name, [
                'room_id' => $room->id,
                'user_room_id' => $tenancy->id,
                'previous_status' => $previousStatus,
                'status' => $status,
            ]);
        });
    }
}
