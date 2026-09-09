<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PengelolaScopeHelper;
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
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user      = Auth::user();
        $clusterId = $request->cluster_id ? (int) $request->cluster_id : null;

        $boardingHouseIds = PengelolaScopeHelper::getBoardingHouseIds($user, $clusterId);

        $query = TransactionLog::with(['room', 'boardingHouse', 'transaction'])
            ->latest('transaction_date');

        if ($boardingHouseIds !== null) {
            $query->whereIn('boarding_house_id', $boardingHouseIds);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('description', 'like', '%' . $request->search . '%')
                    ->orWhere('reference_number', 'like', '%' . $request->search . '%');
            });
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

        $boardingHousesQuery = BoardingHouse::with('rooms');
        PengelolaScopeHelper::applyBoardingHouseScope($boardingHousesQuery, $user);
        $boardingHouses = $boardingHousesQuery->get();

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

    public function updateIncome(Request $request, TransactionLog $transaction)
    {
        abort_unless($transaction->type === 'income', 403, 'Transaksi ini bukan pemasukan manual.');

        $validated = $request->validate([
            'boarding_house_id' => 'required|exists:boarding_houses,id',
            'room_id'           => 'nullable|exists:rooms,id',
            'amount'            => 'required|numeric|min:0',
            'transaction_date'  => 'required|date',
            'description'       => 'required|string',
            'payment_method'    => 'required|string',
        ]);

        $transaction->update([
            'boarding_house_id' => $validated['boarding_house_id'],
            'room_id'           => $validated['room_id'] ?? null,
            'amount'            => $validated['amount'],
            'transaction_date'  => $validated['transaction_date'],
            'description'       => $validated['description'],
            'payment_method'    => $validated['payment_method'],
        ]);

        return Redirect::back()->with('success', 'Pemasukan berhasil diperbarui');
    }

    public function updateExpense(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'boarding_house_id' => 'required|exists:boarding_houses,id',
            'room_id'           => 'nullable|exists:rooms,id',
            'amount'            => 'required|numeric|min:0',
            'expense_date'      => 'required|date',
            'description'       => 'required|string',
            'category'          => 'required|string',
        ]);

        $expense->update([
            'boarding_house_id' => $validated['boarding_house_id'],
            'room_id'           => $validated['room_id'] ?? null,
            'amount'            => $validated['amount'],
            'expense_date'      => $validated['expense_date'],
            'description'       => $validated['description'],
            'category'          => $validated['category'],
        ]);

        return Redirect::back()->with('success', 'Pengeluaran berhasil diperbarui');
    }

    public function destroyIncome(TransactionLog $transaction)
    {
        abort_unless($transaction->type === 'income', 403, 'Hanya pemasukan manual yang bisa dihapus.');

        $transaction->delete();

        return Redirect::back()->with('success', 'Pemasukan berhasil dihapus');
    }

    public function destroyExpense(Expense $expense)
    {
        $expense->delete();

        return Redirect::back()->with('success', 'Pengeluaran berhasil dihapus');
    }
}
