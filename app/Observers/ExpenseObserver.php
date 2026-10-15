<?php

namespace App\Observers;

use App\Models\Expense;
use App\Models\TransactionLog;

class ExpenseObserver
{
    public function saved(Expense $expense): void
    {
        $values = [
            'expense_id' => $expense->id,
            'room_id' => $expense->room_id,
            'boarding_house_id' => $expense->boarding_house_id,
            'type' => 'expense',
            'amount' => abs($expense->amount),
            'status' => 'completed',
            'description' => $expense->description
                ? "{$expense->category} - {$expense->description}" : $expense->category,
            'payment_method' => 'cash',
            'reference_number' => "EXP-{$expense->id}",
            'transaction_date' => $expense->expense_date,
        ];

        $logs = TransactionLog::where('expense_id', $expense->id)->where('type', 'expense');
        if ($logs->exists()) {
            $logs->update($values);
        } else {
            TransactionLog::create($values);
        }
    }
}
