<?php

namespace App\Http\Resources\Admin\FinancialReport;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoardingHouseFinancialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $totalIncome = $this->transactionLogs
            ->whereIn('type', ['payment', 'income'])
            ->where('status', 'completed')
            ->sum('amount');
        $totalExpense = $this->transactionLogs
            ->where('type', 'expense')
            ->sum('amount');
        $netProfit = $totalIncome - $totalExpense;

        $ownerPercentage = $this->persentasi_pemilik ?? 100;
        $ownerShare = ($netProfit * $ownerPercentage) / 100;
        $managementShare = $netProfit - $ownerShare;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'cluster_name' => $this->cluster ? $this->cluster->name : '-',
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'net_profit' => $netProfit,
            'owner_share' => $ownerShare,
            'management_share' => $managementShare,
            'thumbnail' => $this->thumbnail,
            'address' => $this->address,
        ];
    }
}
