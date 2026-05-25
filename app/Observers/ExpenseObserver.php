<?php

namespace App\Observers;

use App\Models\Expense;
use App\Models\TransactionLog;
use Carbon\Carbon;

class ExpenseObserver
{
    /**
     * Handle the Expense "created" event.
     */
    public function created(Expense $expense): void
    {
        $description = $expense->description
            ? "{$expense->category} - {$expense->description}"
            : "{$expense->category}";

        TransactionLog::create([
            'transaction_id' => null,
            'room_id' => $expense->room_id,
            'boarding_house_id' => $expense->boarding_house_id,
            'type' => 'expense',
            'amount' => $expense->amount, // Negative because it's an expense
            'status' => 'completed',
            'description' => $description,
            'payment_method' => 'cash',
            'reference_number' => "EXP-{$expense->id}",
            'transaction_date' => Carbon::parse($expense->expense_date),
        ]);
    }

    /**
     * Handle the Expense "updated" event.
     */
    public function updated(Expense $expense): void
    {
        // Find transaction log by reference number (most reliable)
        $transactionLog = TransactionLog::where('reference_number', "EXP-{$expense->id}")
            ->where('type', 'expense')
            ->first();

        // Fallback: if reference number doesn't exist, try to find by other criteria
        if (!$transactionLog) {
            $transactionLog = TransactionLog::where('room_id', $expense->room_id)
                ->where('boarding_house_id', $expense->boarding_house_id)
                ->where('type', 'expense')
                ->whereDate('transaction_date', Carbon::parse($expense->getOriginal('expense_date'))->format('Y-m-d'))
                ->where('amount', -abs($expense->getOriginal('amount')))
                ->first();
        }

        if ($transactionLog) {
            $description = $expense->description
                ? "{$expense->category} - {$expense->description}"
                : "{$expense->category}";

            $transactionLog->update([
                'amount' => -abs($expense->amount),
                'description' => $description,
                'transaction_date' => Carbon::parse($expense->expense_date),
                'reference_number' => "EXP-{$expense->id}", // Ensure reference number is set
            ]);
        }
    }

    /**
     * Handle the Expense "deleted" event.
     */
    public function deleted(Expense $expense): void
    {
        // Find transaction log by reference number (most reliable)
        $transactionLog = TransactionLog::where('reference_number', "EXP-{$expense->id}")
            ->where('type', 'expense')
            ->first();

        // Fallback: if reference number doesn't exist, try to find by other criteria
        if (!$transactionLog) {
            $transactionLog = TransactionLog::where('room_id', $expense->room_id)
                ->where('boarding_house_id', $expense->boarding_house_id)
                ->where('type', 'expense')
                ->whereDate('transaction_date', Carbon::parse($expense->expense_date)->format('Y-m-d'))
                ->where('amount', -abs($expense->amount))
                ->first();
        }

        if ($transactionLog) {
            $transactionLog->delete();
        }
    }
}

