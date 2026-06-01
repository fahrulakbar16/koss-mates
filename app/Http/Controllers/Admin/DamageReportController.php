<?php

namespace App\Http\Controllers\Admin;

use App\Actions\DamageReport\UpdateDamageReportStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\DamageReport\UpdateDamageReportRequest;
use App\Http\Resources\DamageReport\DamageReportResource;
use App\Models\DamageReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class DamageReportController extends Controller
{
    /**
     * Display a listing of damage reports.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = DamageReport::with(['user', 'userRoom.room.boardingHouse'])
            ->orderBy('created_at', 'desc');

        if ($user->hasRole('Pengelola')) {
            $query->whereHas('userRoom.room.boardingHouse', fn($q) => $q->where('pengelola_id', $user->id));
        } elseif ($user->hasRole('Pemilik')) {
            $query->whereHas('userRoom.room.boardingHouse', fn($q) => $q->where('owner_id', $user->id));
        }

        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 10);
        $reports = $query->paginate($perPage);

        return Inertia::render('Admin/DamageReports/Index', [
            'reports' => DamageReportResource::collection($reports)->response()->getData(true),
            'filters' => [
                'status' => $request->status ?? 'all',
                'search' => $request->search ?? '',
            ],
        ]);
    }

    /**
     * Display the specified damage report.
     */
    public function show(DamageReport $damageReport)
    {
        $damageReport->load(['user', 'userRoom.room.boardingHouse']);

        return Inertia::render('Admin/DamageReports/Show', [
            'report' => new DamageReportResource($damageReport),
        ]);
    }

    /**
     * Update the specified damage report status.
     */
    public function update(UpdateDamageReportRequest $request, DamageReport $damageReport)
    {
        try {
            $updatedReport = app(UpdateDamageReportStatus::class)->execute(
                $damageReport,
                $request->validated()
            );

            // Send WhatsApp notification based on status
            $this->sendWhatsAppNotification($updatedReport);

            return redirect()->back()->with('success', 'Status laporan berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Failed to update damage report: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui status laporan');
        }
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
