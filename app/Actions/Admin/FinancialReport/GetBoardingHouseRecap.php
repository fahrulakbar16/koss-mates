<?php

namespace App\Actions\Admin\FinancialReport;

use App\Models\BoardingHouse;
use App\Models\TransactionLog;
use App\Models\UserRooms;

class GetBoardingHouseRecap
{
    /**
     * Get financial recap for a specific boarding house.
     */
    public function execute(BoardingHouse $boardingHouse, array $filters): array
    {
        $month = $filters['month'] ?? date('m');
        $year = $filters['year'] ?? date('Y');
        $startDate = $filters['start_date'] ?? null;
        $endDate = $filters['end_date'] ?? null;
        $useDateRange = $startDate && $endDate;

        $tenants = UserRooms::with(['user.tenant', 'room', 'plan'])
            ->where('boarding_house_id', $boardingHouse->id)
            ->withSum(['transactionLogs as paid_amount' => function ($query) use ($month, $year, $startDate, $endDate, $useDateRange) {
                if ($useDateRange) {
                    $query->whereBetween('transaction_date', [$startDate, $endDate]);
                } else {
                    $query->whereMonth('transaction_date', $month)
                        ->whereYear('transaction_date', $year);
                }
                $query->whereIn('transaction_logs.type', ['payment', 'income'])
                    ->where('transaction_logs.status', 'completed');
            }], 'amount')
            ->get();

        $expensesQuery = TransactionLog::where('boarding_house_id', $boardingHouse->id)
            ->where('type', 'expense')
            ->orderBy('transaction_date', 'desc');

        if ($useDateRange) {
            $expensesQuery->whereBetween('transaction_date', [$startDate, $endDate]);
        } else {
            $expensesQuery->whereMonth('transaction_date', $month)
                ->whereYear('transaction_date', $year);
        }

        $expenses = $expensesQuery->get();

        $incomeQuery = TransactionLog::where('boarding_house_id', $boardingHouse->id)
            ->whereIn('type', ['payment', 'income'])
            ->where('status', 'completed');

        if ($useDateRange) {
            $incomeQuery->whereBetween('transaction_date', [$startDate, $endDate]);
        } else {
            $incomeQuery->whereMonth('transaction_date', $month)
                ->whereYear('transaction_date', $year);
        }

        $income = $incomeQuery->sum('amount');

        return [
            'tenants' => $tenants,
            'expenses' => $expenses,
            'income' => $income,
        ];
    }
}
