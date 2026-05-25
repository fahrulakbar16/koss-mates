<?php

namespace App\Actions\Refund;

use App\Models\Refund;
use Illuminate\Support\Facades\Storage;

class UpdateRefundAction
{
    public function execute(Refund $refund, array $data)
    {
        if (isset($data['proof']) && $data['proof']) {
            $path = $data['proof']->store('refund-proofs', 'public');
            $refund->proof = $path;

            // Auto update status if proof is uploaded
            if ($refund->status == 'process') {
                $refund->status = 'menunggu konfirmasi';
            }
        } else {
            $refund->status = $data['status'];
        }

        $refund->save();

        return $refund;
    }
}
