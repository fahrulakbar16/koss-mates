<?php

namespace App\Actions\Admin\FinancialReport;

use App\Models\Room;
use App\Models\TransactionLog;

class GetRoomTransactionDetail
{
    /**
     * Get transaction history for a specific room.
     */
    public function execute(Room $room, array $filters): array
    {
        $startDate = $filters['start_date'] ?? date('Y') . '-01-01';
        $endDate = $filters['end_date'] ?? date('Y-m-d');

        $transactions = TransactionLog::where('room_id', $room->id)
            ->whereBetween('transaction_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereIn('type', ['payment', 'income'])
            ->where('status', 'completed')
            ->with(['transaction.user', 'room'])
            ->orderBy('transaction_date', 'desc')
            ->get();

        return [
            'transactions' => $transactions,
            'total_income' => $transactions->sum('amount'),
        ];
    }
}
