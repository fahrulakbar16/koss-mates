<?php

namespace App\Actions\Transaction;

class GetPaymentListPaginate
{
    public function execute($user, $data)
    {
        $transaction =  $user->transactions()->with(['room.boardingHouse', 'paymentActive'])
            ->withSum('paymentSuccess', 'amount')
            ->when(!empty($data['status']), function ($query) use ($data) {
                $query->where('status', $data['status']);
            })
            ->when(!empty($data['type']), function ($query) use ($data) {
                $query->where('type', $data['type']);
            })
            ->latest()
            ->cursorPaginate(10);

        return $transaction;
    }
}
