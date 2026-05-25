<?php

namespace App\Actions\Expense;

use App\Models\BoardingHouse;
use App\Models\Expense;

class StoreExpense
{
    /**
     * Execute the action to store a new expense.
     *
     * @param BoardingHouse $boardingHouse
     * @param array $data
     * @param \Illuminate\Http\UploadedFile|null $receiptFile
     * @return Expense
     */
    public function execute(BoardingHouse $boardingHouse, array $data, $receiptFile = null): Expense
    {
        $receiptPath = null;
        if ($receiptFile) {
            $receiptPath = $receiptFile->store('receipts', 'public');
        }

        return $boardingHouse->expenses()->create([
            'description' => $data['description'],
            'amount' => $data['amount'],
            'expense_date' => $data['date'],
            'room_id' => $data['room_id'] ?? null,
            'category' => 'other', // Default category
            'receipt_path' => $receiptPath,
        ]);
    }
}
