<?php

namespace App\Actions\Dashboard;

use App\Models\BoardingHouse;
use App\Models\Cluster;
use App\Models\DamageReport;
use App\Models\TransactionLog;
use App\Models\LogActivity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GetDashboardMetricsAction
{
    /**
     * Get dashboard metrics for manager dashboard.
     *
     * @param int|null $clusterId Optional cluster ID to filter metrics
     * @param User|null $user The current authenticated user for role-based filtering
     */
    public function execute($clusterId = null, User $user = null): array
    {
        try {
            // Current and previous month dates
            $currentMonthStart = Carbon::now()->startOfMonth();
            $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();
            $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth();

            // Check if user is an owner (Pemilik)
            $isPemilik = $user && $user->hasRole('Pemilik');

            // Get boarding house IDs for this cluster (if cluster filter is applied)
            $boardingHouseIds = null;
            if ($clusterId || $isPemilik) {
                $query = BoardingHouse::query();

                if ($clusterId) {
                    $query->where('cluster_id', $clusterId);
                }

                if ($isPemilik) {
                    $query->where('owner_id', $user->id);
                }

                $boardingHouseIds = $query->pluck('id');
            }

            // Monthly Income (Pemasukan) - filtered by cluster
            $currentMonthIncome = TransactionLog::where('status', 'completed')
                ->where('type', 'payment')
                ->where('transaction_date', '>=', $currentMonthStart)
                ->when($boardingHouseIds, function ($query) use ($boardingHouseIds) {
                    $query->whereIn('boarding_house_id', $boardingHouseIds);
                })
                ->sum('amount');

            $previousMonthIncome = TransactionLog::where('status', 'completed')
                ->where('type', 'payment')
                ->whereBetween('transaction_date', [$previousMonthStart, $previousMonthEnd])
                ->when($boardingHouseIds, function ($query) use ($boardingHouseIds) {
                    $query->whereIn('boarding_house_id', $boardingHouseIds);
                })
                ->sum('amount');

            $incomePercentage = $previousMonthIncome > 0
                ? round((($currentMonthIncome - $previousMonthIncome) / $previousMonthIncome) * 100, 2)
                : 0;

            // Monthly Expenses (Pengeluaran) - filtered by cluster
            $currentMonthExpenses = TransactionLog::where('type', 'expense')
                ->where('transaction_date', '>=', $currentMonthStart)
                ->when($boardingHouseIds, function ($query) use ($boardingHouseIds) {
                    $query->whereIn('boarding_house_id', $boardingHouseIds);
                })
                ->sum('amount');

            $previousMonthExpenses = TransactionLog::where('type', 'expense')
                ->whereBetween('transaction_date', [$previousMonthStart, $previousMonthEnd])
                ->when($boardingHouseIds, function ($query) use ($boardingHouseIds) {
                    $query->whereIn('boarding_house_id', $boardingHouseIds);
                })
                ->sum('amount');

            $expensePercentage = $previousMonthExpenses > 0
                ? round((($currentMonthExpenses - $previousMonthExpenses) / $previousMonthExpenses) * 100, 2)
                : 0;

            // Calculate Owner & Management Shares (Iterate through each house for accuracy)
            $totalOwnerShare = 0;
            $totalManagementShare = 0;

            $allHouses = BoardingHouse::when($boardingHouseIds, function ($query) use ($boardingHouseIds) {
                    $query->whereIn('id', $boardingHouseIds);
                })
                ->get();

            foreach ($allHouses as $house) {
                $houseIncome = TransactionLog::where('boarding_house_id', $house->id)
                    ->where('status', 'completed')
                    ->where('type', 'payment')
                    ->where('transaction_date', '>=', $currentMonthStart)
                    ->sum('amount');

                $houseExpense = TransactionLog::where('boarding_house_id', $house->id)
                    ->where('type', 'expense')
                    ->where('transaction_date', '>=', $currentMonthStart)
                    ->sum('amount');

                $houseNetProfit = $houseIncome - $houseExpense;
                $ownerPercentage = $house->persentasi_pemilik ?? 100;
                $houseOwnerShare = ($houseNetProfit * $ownerPercentage) / 100;
                $houseManagementShare = $houseNetProfit - $houseOwnerShare;

                $totalOwnerShare += $houseOwnerShare;
                $totalManagementShare += $houseManagementShare;
            }

            $currentNetProfit = $currentMonthIncome - $currentMonthExpenses;

            // Occupied Rooms Count (for top metric) - filtered by cluster
            $occupiedRoomsCount = \App\Models\Room::whereHas('penyewaAktif')
                ->when($boardingHouseIds, function ($query) use ($boardingHouseIds) {
                    $query->whereIn('boarding_house_id', $boardingHouseIds);
                })
                ->count();

            $totalRooms = \App\Models\Room::when($boardingHouseIds, function ($query) use ($boardingHouseIds) {
                $query->whereIn('boarding_house_id', $boardingHouseIds);
            })
                ->count();

            // New Tenants (recently updated to checked_in status this month) - filtered by cluster
            $newTenantsCount = \App\Models\UserRooms::where('status', 'checked_in')
                ->where('created_at', '>=', $currentMonthStart)
                ->when($boardingHouseIds, function ($query) use ($boardingHouseIds) {
                    $query->whereHas('room', function ($q) use ($boardingHouseIds) {
                        $q->whereIn('boarding_house_id', $boardingHouseIds);
                    });
                })
                ->distinct('user_id')
                ->count('user_id');

            // New Reports Count - filtered by cluster
            $newReportsCount = DamageReport::whereIn('status', ['pending', 'in_progress'])
                ->when($boardingHouseIds, function ($query) use ($boardingHouseIds) {
                    $query->whereHas('userRoom.room', function ($q) use ($boardingHouseIds) {
                        $q->whereIn('boarding_house_id', $boardingHouseIds);
                    });
                })
                ->count();

            // Boarding Houses - filtered by cluster
            $boardingHouses = BoardingHouse::with(['cluster', 'rooms.penyewaAktif'])
                ->when($clusterId, function ($query) use ($clusterId) {
                    $query->where('cluster_id', $clusterId);
                })
                ->when($isPemilik, function ($query) use ($user) {
                    $query->where('owner_id', $user->id);
                })
                ->get()
                ->map(function ($bh) {
                    $totalRooms = $bh->rooms->count();
                    $occupiedRooms = $bh->rooms->filter(function ($room) {
                        return $room->penyewaAktif->count() > 0;
                    })->count();

                    return [
                        'id' => $bh->id,
                        'name' => $bh->name,
                        'cluster_id' => $bh->cluster_id,
                        'cluster_name' => $bh->cluster->name ?? 'Tanpa Cluster',
                        'total_rooms' => $totalRooms,
                        'occupied_rooms' => $occupiedRooms,
                        'occupancy_percentage' => $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0,
                    ];
                });

            // Clusters for filter
            $clusters = Cluster::withCount(['boardingHouses' => function ($query) use ($user, $isPemilik) {
                if ($isPemilik) {
                    $query->where('owner_id', $user->id);
                }
            }])
                ->having('boarding_houses_count', '>', 0)
                ->get()
                ->map(function ($cluster) {
                    return [
                        'id' => $cluster->id,
                        'name' => $cluster->name,
                        'boarding_houses_count' => $cluster->boarding_houses_count,
                    ];
                });

            // Recent Transactions - Income (Pemasukan) - filtered by cluster
            $recentIncome = TransactionLog::with(['transaction.user', 'room.boardingHouse'])
                ->where('status', 'completed')
                ->where('type', 'payment')
                ->when($boardingHouseIds, function ($query) use ($boardingHouseIds) {
                    $query->whereIn('boarding_house_id', $boardingHouseIds);
                })
                ->latest('transaction_date')
                ->take(10)
                ->get()
                ->map(function ($log) {
                    return [
                        'id' => $log->id,
                        'description' => $log->description,
                        'date' => $log->transaction_date->format('d M Y'),
                        'amount' => $log->amount,
                    ];
                });

            // Recent Expenses (Pengeluaran) - filtered by cluster
            $recentExpenses = TransactionLog::with(['room.boardingHouse'])
                ->where('type', 'expense')
                ->when($boardingHouseIds, function ($query) use ($boardingHouseIds) {
                    $query->whereIn('boarding_house_id', $boardingHouseIds);
                })
                ->latest('transaction_date')
                ->take(10)
                ->get()
                ->map(function ($expense) {
                    return [
                        'id' => $expense->id,
                        'description' => $expense->description,
                        'date' => $expense->transaction_date->format('d M Y'),
                        'amount' => $expense->amount,
                    ];
                });

            // Recent Damage Reports - filtered by cluster
            $recentReports = DamageReport::with(['user', 'userRoom.room.boardingHouse'])
                ->whereIn('status', ['pending', 'in_progress'])
                ->when($boardingHouseIds, function ($query) use ($boardingHouseIds) {
                    $query->whereHas('userRoom.room', function ($q) use ($boardingHouseIds) {
                        $q->whereIn('boarding_house_id', $boardingHouseIds);
                    });
                })
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($report) {
                    return [
                        'id' => $report->id,
                        'user_name' => $report->user->name ?? 'N/A',
                        'room_name' => $report->userRoom->room->name ?? 'N/A',
                        'boarding_house' => $report->userRoom->room->boardingHouse->name ?? 'N/A',
                        'description' => $report->description,
                        'status' => $report->status,
                        'created_at' => $report->created_at->diffForHumans(),
                    ];
                });

            // Recent Activities
            $recentActivities = LogActivity::with('user')
                ->latest()
                ->when($user && !$user->hasAnyRole(['Superadmin', 'Pengelola']), function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->take(4)
                ->get()
                ->map(function ($log) {
                    return [
                        'id' => $log->id,
                        'subject' => $log->subject,
                        'user_name' => $log->user->name ?? 'Guest',
                        'date' => $log->created_at->format('d M Y H:i'),
                        'human_time' => $log->created_at->diffForHumans(),
                    ];
                });

            return [
                // Top Metrics
                'monthly_income' => $currentMonthIncome,
                'income_percentage' => $incomePercentage,
                'monthly_expenses' => $currentMonthExpenses,
                'expense_percentage' => $expensePercentage,
                'net_profit' => $currentNetProfit,
                'owner_share' => $totalOwnerShare,
                'management_share' => $totalManagementShare,
                'occupied_rooms' => $occupiedRoomsCount,
                'total_rooms' => $totalRooms,
                'new_tenants' => $newTenantsCount,
                'new_reports' => $newReportsCount,

                // Boarding Houses & Clusters
                'boarding_houses' => $boardingHouses,
                'clusters' => $clusters,

                // Transactions
                'recent_income' => $recentIncome,
                'recent_expenses' => $recentExpenses,

                // Reports
                'recent_reports' => $recentReports,

                // Activities
                'recent_activities' => $recentActivities,
            ];
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Dashboard Metrics Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Fallback data
            return [
                'monthly_income' => 0,
                'income_percentage' => 0,
                'monthly_expenses' => 0,
                'expense_percentage' => 0,
                'occupied_rooms' => 0,
                'total_rooms' => 0,
                'new_tenants' => 0,
                'new_reports' => 0,
                'boarding_houses' => [],
                'clusters' => [],
                'recent_income' => [],
                'recent_expenses' => [],
                'recent_reports' => [],
                'recent_activities' => [],
            ];
        }
    }
}
