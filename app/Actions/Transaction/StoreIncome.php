<?php

namespace App\Actions\Transaction;

use App\Models\TransactionLog;

class StoreIncome
{
    /**
     * Execute the action to store a new income transaction.
     *
     * @param array $data
     * @return TransactionLog
     */
    public function execute(array $data): TransactionLog
    {
        return TransactionLog::create([
            'boarding_house_id' => $data['boarding_house_id'],
            'room_id' => $data['room_id'] ?? null,
            'amount' => $data['amount'],
            'transaction_date' => $data['transaction_date'],
            'description' => $data['description'],
            'type' => 'income',
            'status' => 'completed',
            'payment_method' => $data['payment_method'],
            'reference_number' => 'INC-' . time(),
        ]);
    }
}
