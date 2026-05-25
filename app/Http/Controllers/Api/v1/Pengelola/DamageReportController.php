<?php

namespace App\Http\Controllers\Api\v1\Pengelola;

use App\Actions\DamageReport\UpdateDamageReportStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\DamageReport\UpdateDamageReportRequest;
use App\Http\Resources\DamageReport\DamageReportResource;
use App\Models\DamageReport;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DamageReportController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of damage reports.
     */
    public function index(Request $request)
    {
        $query = DamageReport::with(['user', 'userRoom.room.boardingHouse'])
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $reports = $query->cursorPaginate(15);

        return $this->apiResponse(DamageReportResource::collection($reports)->response()->getData(true), 'Data Berhasil Diambil');
    }

    /**
     * Display the specified damage report.
     */
    public function show($damageReportId)
    {
        $damageReport = DamageReport::with(['user.tenant', 'userRoom.room.boardingHouse'])->findOrFail($damageReportId);

        return $this->apiResponse(new DamageReportResource($damageReport), 'Data Berhasil Diambil');
    }

    /**
     * Update the specified damage report status.
     */
    public function updateStatus(UpdateDamageReportRequest $request, $damageReportId)
    {
        $damageReport = DamageReport::findOrFail($damageReportId);
        $updatedReport = app(UpdateDamageReportStatus::class)->execute(
            $damageReport,
            $request->validated()
        );

        // Send WhatsApp notification based on status
        $this->sendWhatsAppNotification($updatedReport);

        return $this->apiResponse(new DamageReportResource($updatedReport), 'Status laporan berhasil diperbarui');
    }

    /**
     * Send WhatsApp notification to tenant.
     */
    private function sendWhatsAppNotification(DamageReport $report)
    {
        try {
            $report->load(['user.tenant', 'userRoom.room.boardingHouse']);

            if (!$report->user->tenant || !$report->user->tenant->phone) {
                return;
            }

            $fonnteService = app(\App\Services\FonnteApiService::class);
            $formattedPhone = preg_replace('/^0/', '62', $report->user->tenant->phone);

            $roomName = $report->userRoom->room->nama_kamar ?? 'kamar Anda';
            $bhName = $report->userRoom->room->boardingHouse->nama_kos ?? '';

            $message = match ($report->status) {
                'in_progress' => "Halo {$report->user->name},\n\n" .
                    "Laporan kerusakan Anda '{$report->title}' sedang dalam proses perbaikan.\n\n" .
                    "Kamar: {$roomName}\n" .
                    ($bhName ? "Kos: {$bhName}\n\n" : "\n") .
                    "Terima kasih atas kesabaran Anda.",

                'resolved' => "Halo {$report->user->name},\n\n" .
                    "Laporan kerusakan Anda '{$report->title}' telah selesai diperbaiki.\n\n" .
                    "Kamar: {$roomName}\n" .
                    ($bhName ? "Kos: {$bhName}\n\n" : "\n") .
                    ($report->admin_notes ? "Catatan: {$report->admin_notes}\n\n" : '') .
                    "Terima kasih atas laporannya.",

                'rejected' => "Halo {$report->user->name},\n\n" .
                    "Laporan kerusakan Anda '{$report->title}' tidak dapat diproses.\n\n" .
                    "Kamar: {$roomName}\n" .
                    ($bhName ? "Kos: {$bhName}\n\n" : "\n") .
                    ($report->admin_notes ? "Alasan: {$report->admin_notes}\n\n" : '') .
                    "Silakan hubungi kami jika ada pertanyaan.",

                default => null,
            };

            if ($message) {
                $fonnteService->sendMessage($formattedPhone, $message);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp notification for damage report: ' . $e->getMessage());
        }
    }
}
