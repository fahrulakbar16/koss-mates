<?php

namespace App\Actions\CheckIn;

use App\Models\UserRooms;
use App\Helpers\LogActivityHelper;
use App\Services\FonnteApiService;
use Illuminate\Support\Facades\Log;

class ApproveCheckInRequest
{
    protected $fonnteService;

    public function __construct(FonnteApiService $fonnteService)
    {
        $this->fonnteService = $fonnteService;
    }

    public function execute(int $id): UserRooms
    {
        $userRoom = UserRooms::with(['user', 'user.tenant', 'room.boardingHouse', 'plan'])->findOrFail($id);

        // Update status
        $userRoom->update([
            'verifikasi_admin' => 1,
            'status' => 'checked_in',
            'start_date' => now(),
            'end_date' => $this->generateEndDate(now(), $userRoom->plan->duration),
        ]);


        // Send WhatsApp notification
        $this->sendWhatsAppNotification(
            $userRoom->user->tenant->phone,
            "Halo {$userRoom->user->name},\n\n" .
                "Selamat! Permintaan check-in Anda untuk kamar {$userRoom->room->nama_kamar} di {$userRoom->room->boardingHouse->nama_kos} telah disetujui.\n\n" .
                "Anda sudah bisa menempati kamar mulai {$userRoom->start_date}.\n\n" .
                "Terima kasih telah menggunakan layanan kami."
        );

        LogActivityHelper::addToLog('Menyetujui check-in: ' . $userRoom->user->name . ' di ' . $userRoom->room->nama_kamar, [
            'user_room_id' => $userRoom->id,
            'user_name' => $userRoom->user->name,
            'room_name' => $userRoom->room->nama_kamar,
            'start_date' => $userRoom->start_date,
            'end_date' => $userRoom->end_date,
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

    public function generateEndDate($startDate, $duration)
    {
        $totalMonths = $duration;
        return $startDate ? $startDate->copy()->addMonthsNoOverflow($totalMonths) : null;
    }
}
