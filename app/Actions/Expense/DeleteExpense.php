<?php

namespace App\Actions\Expense;

use App\Models\Expense;
use Illuminate\Support\Facades\Storage;

class DeleteExpense
{
    /**
     * Execute the action to delete an expense.
     *
     * @param Expense $expense
     * @return bool|null
     */
    public function execute(Expense $expense): ?bool
    {
        if ($expense->receipt_path && Storage::disk('public')->exists($expense->receipt_path)) {
            Storage::disk('public')->delete($expense->receipt_path);
        }

        return $expense->delete();
    }
}
