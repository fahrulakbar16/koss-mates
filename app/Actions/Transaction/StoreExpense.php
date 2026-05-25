<?php

namespace App\Actions\Transaction;

use App\Models\Expense;

class StoreExpense
{
    /**
     * Execute the action to store a new expense.
     *
     * @param array $data
     * @return Expense
     */
    public function execute(array $data): Expense
    {
        return Expense::create([
            'boarding_house_id' => $data['boarding_house_id'],
            'room_id' => $data['room_id'] ?? null,
            'amount' => $data['amount'],
            'expense_date' => $data['expense_date'],
            'description' => $data['description'],
            'category' => $data['category'],
            'user_id' => auth()->user()?->id,
            'status' => 'selesai',
        ]);
    }
}
