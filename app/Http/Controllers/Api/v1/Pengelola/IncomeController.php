<?php

namespace App\Http\Controllers\Api\v1\Pengelola;

use App\Actions\Transaction\StoreIncome;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\StoreIncomeRequest;
use App\Models\BoardingHouse;
use App\Traits\ApiResponse;

class IncomeController extends Controller
{
    use ApiResponse;

    /**
     * Menampilkan daftar pemasukan untuk boarding house.
     */
    public function index($clusterId, $boardingHouseId)
    {
        $boardingHouse = BoardingHouse::where('cluster_id', $clusterId)->findOrFail($boardingHouseId);
        $incomes = $boardingHouse->transactionLogs()
            ->where('type', 'income')
            ->latest()
            ->get();

        return $this->apiResponse($incomes, 'Data pemasukan berhasil diambil');
    }

    /**
     * Menyimpan pemasukan baru.
     */
    public function store(StoreIncomeRequest $request, $clusterId, $boardingHouseId)
    {
        // Ensure boarding house belongs to cluster
        BoardingHouse::where('cluster_id', $clusterId)->findOrFail($boardingHouseId);

        $income = app(StoreIncome::class)->execute($request->validated());

        return $this->apiResponse($income, 'Pemasukan berhasil ditambahkan');
    }
}
