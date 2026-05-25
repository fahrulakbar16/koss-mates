<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoardingHouse;
use App\Models\TransactionLog;
use App\Models\Expense;
use App\Models\Room;
use App\Http\Requests\Transaction\StoreIncomeRequest;
use App\Http\Requests\Transaction\StoreExpenseRequest;
use App\Actions\Transaction\StoreIncome;
use App\Actions\Transaction\StoreExpense;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = TransactionLog::with(['room', 'boardingHouse', 'transaction'])
            ->latest('created_at');

        if ($request->search) {
            $query->where('description', 'like', '%' . $request->search . '%')
                  ->orWhere('reference_number', 'like', '%' . $request->search . '%');
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('transaction_date', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        $transactions = $query->paginate(10)->withQueryString();

        $boardingHouses = BoardingHouse::with('rooms')->get();

        return Inertia::render('Admin/Transaction/Index', [
            'transactions' => $transactions,
            'boardingHouses' => $boardingHouses,
            'filters' => $request->only(['search', 'type', 'start_date', 'end_date']),
        ]);
    }

    public function storeIncome(StoreIncomeRequest $request)
    {
        app(StoreIncome::class)->execute($request->validated());

        return Redirect::back()->with('success', 'Pemasukan berhasil ditambahkan');
    }

    public function storeExpense(StoreExpenseRequest $request)
    {
        app(StoreExpense::class)->execute($request->validated());

        return Redirect::back()->with('success', 'Pengeluaran berhasil ditambahkan');
    }
}
