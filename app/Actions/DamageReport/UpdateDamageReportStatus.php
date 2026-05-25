<?php

namespace App\Actions\DamageReport;

use App\Models\DamageReport;
use App\Helpers\LogActivityHelper;

class UpdateDamageReportStatus
{
    /**
     * Update damage report status.
     *
     * @param DamageReport $damageReport
     * @param array $data
     * @return DamageReport
     */
    public function execute(DamageReport $damageReport, array $data): DamageReport
    {
        $damageReport->update([
            'status' => $data['status'],
            'admin_notes' => $data['admin_notes'] ?? $damageReport->admin_notes,
        ]);

        if ($data['status'] === 'resolved' && !empty($data['repair_cost'])) {
            $repairProofPath = null;
            if (isset($data['repair_proof']) && $data['repair_proof'] instanceof \Illuminate\Http\UploadedFile) {
                $repairProofPath = $data['repair_proof']->store('repair-proofs', 'public');
            }

            $damageReport->load('userRoom.room');
            $userRoom = $damageReport->userRoom;

            \App\Models\Expense::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'boarding_house_id' => $userRoom?->boarding_house_id,
                'room_id' => $userRoom?->room_id,
                'category' => 'repair',
                'description' => 'Biaya perbaikan: ' . $damageReport->title,
                'amount' => $data['repair_cost'],
                'expense_date' => now(),
                'receipt_path' => $repairProofPath,
                'status' => 'selesai',
            ]);
        }

        LogActivityHelper::addToLog('Memperbarui status laporan kerusakan: ' . $damageReport->title, [
            'id' => $damageReport->id,
            'title' => $damageReport->title,
            'status' => $data['status'],
        ]);

        return $damageReport->fresh();
    }
}
