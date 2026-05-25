<?php

namespace App\Actions\Admin\FinancialReport;

use App\Models\BoardingHouse;
use App\Models\TransactionLog;
use Illuminate\Support\Collection;

class GetBoardingHouseDetailedReport
{
    /**
     * Get detailed report data for a specific boarding house.
     */
    public function execute(BoardingHouse $boardingHouse, array $filters): array
    {
        $month = $filters['month'] ?? date('m');
        $year = $filters['year'] ?? date('Y');

        $incomes = TransactionLog::where('boarding_house_id', $boardingHouse->id)
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->whereIn('type', ['payment', 'income'])
            ->where('status', 'completed')
            ->with(['transaction.user', 'room'])
            ->orderBy('transaction_date', 'desc')
            ->get();

        $expenses = TransactionLog::where('boarding_house_id', $boardingHouse->id)
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->where('type', 'expense')
            ->orderBy('transaction_date', 'desc')
            ->get();

        return [
            'incomes' => $incomes,
            'expenses' => $expenses,
            'total_income' => $incomes->sum('amount'),
            'total_expense' => $expenses->sum('amount'),
        ];
    }
}
