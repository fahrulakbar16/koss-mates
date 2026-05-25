<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoardingHouse;
use App\Models\Expense;
use App\Http\Requests\Expense\StoreExpenseRequest;
use App\Http\Requests\Expense\UpdateExpenseRequest;
use App\Http\Requests\Expense\DeleteExpenseRequest;
use App\Actions\Expense\StoreExpense;
use App\Actions\Expense\UpdateExpense;
use App\Actions\Expense\DeleteExpense;
use Illuminate\Support\Facades\Redirect;

class ExpenseController extends Controller
{
    /**
     * Store a newly created expense in storage.
     */
    public function store(StoreExpenseRequest $request, BoardingHouse $boardingHouse)
    {
        app(StoreExpense::class)->execute($boardingHouse, $request->validated(), $request->file('receipt'));

        return Redirect::back()->with('success', 'Pengeluaran berhasil ditambahkan');
    }

    /**
     * Update the specified expense in storage.
     */
    public function update(UpdateExpenseRequest $request, BoardingHouse $boardingHouse, Expense $expense)
    {
        app(UpdateExpense::class)->execute($expense, $request->validated(), $request->file('receipt'));

        return Redirect::back()->with('success', 'Pengeluaran berhasil diperbarui');
    }

    /**
     * Remove the specified expense from storage.
     */
    public function destroy(DeleteExpenseRequest $request, BoardingHouse $boardingHouse, Expense $expense)
    {
        app(DeleteExpense::class)->execute($expense);

        return Redirect::back()->with('success', 'Pengeluaran berhasil dihapus');
    }
}
