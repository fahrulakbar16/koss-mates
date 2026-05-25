<?php

namespace App\Actions\Expense;

use App\Models\Expense;
use Illuminate\Support\Facades\Storage;

class UpdateExpense
{
    /**
     * Execute the action to update an expense.
     *
     * @param Expense $expense
     * @param array $data
     * @param \Illuminate\Http\UploadedFile|null $receiptFile
     * @return bool
     */
    public function execute(Expense $expense, array $data, $receiptFile = null): bool
    {
        $updateData = [
            'description' => $data['description'],
            'amount' => $data['amount'],
            'expense_date' => $data['date'],
            'room_id' => $data['room_id'] ?? null,
        ];

        // Handle receipt upload
        if ($receiptFile) {
            // Delete old receipt if exists
            if ($expense->receipt_path && Storage::disk('public')->exists($expense->receipt_path)) {
                Storage::disk('public')->delete($expense->receipt_path);
            }
            $updateData['receipt_path'] = $receiptFile->store('receipts', 'public');
        }

        return $expense->update($updateData);
    }
}
