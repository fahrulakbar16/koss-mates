<?php

namespace App\Actions\Admin\FinancialReport;

use App\Models\BoardingHouse;
use App\Models\TransactionLog;

class GetTransactionByBoardingHouse
{
    /**
     * Get financial recap for a specific boarding house.
     */
    public function execute(BoardingHouse $boardingHouse, array $filters)
    {
        $month = $filters['month'] ?? date('m');
        $year = $filters['year'] ?? date('Y');
        $startDate = $filters['start_date'] ?? null;
        $endDate = $filters['end_date'] ?? null;
        $useDateRange = $startDate && $endDate;

        $transactions = $boardingHouse->rooms()->withSum(['transactionLogs as paid_amount_sum' => function ($q) use ($month, $year, $startDate, $endDate, $useDateRange) {
            if ($useDateRange) {
                $q->whereBetween('transaction_date', [$startDate, $endDate]);
            } else {
                $q->whereMonth('transaction_date', $month)
                    ->whereYear('transaction_date', $year);
            }
            $q->whereIn('type', ['payment', 'income'])
                ->where('status', 'completed');
        }], 'amount')->with([
            'transactionLogs' => fn($q) => $q->orderBy('transaction_date', 'desc'),
            'transactionLogs.transaction.user',
            'transactionLogs.room',
        ])->get();

        return $transactions;
    }
}
