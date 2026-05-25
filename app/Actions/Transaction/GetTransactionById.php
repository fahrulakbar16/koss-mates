<?php

namespace App\Actions\Transaction;

class GetTransactionById
{
    public function execute($user, $id)
    {
        $transaction =  $user->transactions()->with(['room.boardingHouse', 'paymentActive'])
            ->withSum('paymentSuccess', 'amount')
            ->where('id', $id)
            ->firstOrFail();

        return $transaction;
    }
}
