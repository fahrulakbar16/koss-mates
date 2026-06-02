<?php

namespace App\Actions\Admin\FinancialReport;

use App\Models\BoardingHouse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class GetBoardingHouseFinancialList
{
    /**
     * Get paginated list of boarding houses for financial report.
     */
    public function execute(array $filters, $user): LengthAwarePaginator
    {
        $month = $filters['month'] ?? date('m');
        $year = $filters['year'] ?? date('Y');
        $search = $filters['search'] ?? null;
        $clusterId = $filters['cluster_id'] ?? null;
        $startDate = $filters['start_date'] ?? null;
        $endDate = $filters['end_date'] ?? null;
        $useDateRange = $startDate && $endDate;

        $query = BoardingHouse::query()
            ->with(['cluster', 'transactionLogs' => function ($q) use ($month, $year, $startDate, $endDate, $useDateRange) {
                $q->whereIn('type', ['payment', 'income', 'expense'])
                    ->where('status', 'completed');

                if ($useDateRange) {
                    $q->whereBetween('transaction_date', [$startDate, $endDate]);
                } else {
                    $q->whereMonth('transaction_date', $month)
                        ->whereYear('transaction_date', $year);
                }
            }]);

        if ($user->hasRole('Pemilik')) {
            $query->where('owner_id', $user->id);
        } elseif ($user->hasRole('Pengelola')) {
            $query->where('pengelola_id', $user->id);
        }

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($clusterId) {
            $query->where('cluster_id', $clusterId);
        }

        return $query->paginate(10)->withQueryString();
    }
}
