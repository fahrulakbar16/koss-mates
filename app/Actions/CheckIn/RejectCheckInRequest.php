<?php

namespace App\Actions\CheckIn;

use App\Models\UserRooms;
use App\Helpers\LogActivityHelper;
use App\Services\FonnteApiService;
use Illuminate\Support\Facades\Log;

class RejectCheckInRequest
{
    protected $fonnteService;

    public function __construct(FonnteApiService $fonnteService)
    {
        $this->fonnteService = $fonnteService;
    }

    public function execute(int $id, ?string $reason = null): UserRooms
    {
        $userRoom = UserRooms::with(['user', 'user.tenant', 'room.boardingHouse'])->findOrFail($id);

        // Reset verification
        $userRoom->update([
            'verifikasi_admin' => 0,
            'foto_kamar' => null,
        ]);

        $rejectionReason = $reason ?? 'Foto tidak sesuai atau tidak jelas';

        // Send WhatsApp notification
        $this->sendWhatsAppNotification(
            $userRoom->user->tenant->phone,
            "Halo {$userRoom->user->name},\n\n" .
                "Mohon maaf, permintaan check-in Anda untuk kamar {$userRoom->room->nama_kamar} di {$userRoom->room->boardingHouse->nama_kos} ditolak.\n\n" .
                "Alasan: {$rejectionReason}\n\n" .
                "Silakan upload ulang foto kamar yang lebih jelas."
        );

        LogActivityHelper::addToLog('Menolak check-in: ' . $userRoom->user->name . ' di ' . $userRoom->room->nama_kamar, [
            'user_room_id' => $userRoom->id,
            'user_name' => $userRoom->user->name,
            'room_name' => $userRoom->room->nama_kamar,
            'reason' => $rejectionReason,
        ]);

        return $userRoom;
    }

    /**
     * Send WhatsApp notification via Fonnte
     */
    private function sendWhatsAppNotification($phoneNumber, $message)
    {
        try {
            // Format phone number (remove leading 0, add 62)
            $formattedPhone = preg_replace('/^0/', '62', $phoneNumber);

            $this->fonnteService->sendMessage($formattedPhone, $message);
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp notification: ' . $e->getMessage());
        }
    }
}
