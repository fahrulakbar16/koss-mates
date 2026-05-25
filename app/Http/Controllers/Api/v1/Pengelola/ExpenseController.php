<?php

namespace App\Http\Controllers\Api\v1\Pengelola;

use App\Actions\Expense\DeleteExpense;
use App\Actions\Expense\StoreExpense;
use App\Actions\Expense\UpdateExpense;
use App\Http\Controllers\Controller;
use App\Http\Requests\Expense\DeleteExpenseRequest;
use App\Http\Requests\Expense\StoreExpenseRequest;
use App\Http\Requests\Expense\UpdateExpenseRequest;
use App\Models\BoardingHouse;
use App\Models\Expense;
use App\Traits\ApiResponse;

class ExpenseController extends Controller
{
    use ApiResponse;

    /**
     * Menampilkan daftar pengeluaran untuk boarding house.
     */
    public function index($clusterId, $boardingHouseId)
    {
        $boardingHouse = BoardingHouse::where('cluster_id', $clusterId)->findOrFail($boardingHouseId);
        $expenses = $boardingHouse->expenses()->latest()->get();

        return $this->apiResponse($expenses, 'Data pengeluaran berhasil diambil');
    }

    /**
     * Menyimpan pengeluaran baru.
     */
    public function store(StoreExpenseRequest $request, $clusterId, $boardingHouseId)
    {
        $boardingHouse = BoardingHouse::where('cluster_id', $clusterId)->findOrFail($boardingHouseId);

        $expense = app(StoreExpense::class)->execute(
            $boardingHouse,
            $request->validated(),
            $request->file('receipt')
        );

        return $this->apiResponse($expense, 'Pengeluaran berhasil ditambahkan');
    }

    /**
     * Memperbarui pengeluaran.
     */
    public function update(UpdateExpenseRequest $request, $clusterId, $boardingHouseId, $id)
    {
        $expense = Expense::where('boarding_house_id', $boardingHouseId)->findOrFail($id);

        app(UpdateExpense::class)->execute(
            $expense,
            $request->validated(),
            $request->file('receipt')
        );

        return $this->apiResponse($expense->refresh(), 'Pengeluaran berhasil diperbarui');
    }

    /**
     * Menghapus pengeluaran.
     */
    public function destroy(DeleteExpenseRequest $request, $clusterId, $boardingHouseId, $id)
    {
        $expense = Expense::where('boarding_house_id', $boardingHouseId)->findOrFail($id);

        app(DeleteExpense::class)->execute($expense);

        return $this->apiResponse(null, 'Pengeluaran berhasil dihapus');
    }
}
