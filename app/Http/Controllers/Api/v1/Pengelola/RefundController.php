<?php

namespace App\Http\Controllers\Api\v1\Pengelola;

use App\Actions\Refund\UpdateRefundAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateRefundRequest;
use App\Models\Refund;
use App\Traits\ApiResponse;

class RefundController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $refunds = Refund::with(['user', 'boardingHouse'])
            ->latest()
            ->paginate(10);

        return $this->apiResponse($refunds, 'Data berhasil diambil.');
    }

    public function update(UpdateRefundRequest $request, Refund $refund)
    {
        app(UpdateRefundAction::class)->execute($refund, $request->validated());

        return $this->apiResponse(null, 'Status pengembalian dana diperbarui.');
    }
}
