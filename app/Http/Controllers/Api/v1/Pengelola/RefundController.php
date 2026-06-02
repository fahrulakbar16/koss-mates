<?php

namespace App\Http\Controllers\Api\v1\Pengelola;

use App\Actions\Refund\UpdateRefundAction;
use App\Helpers\PengelolaScopeHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateRefundRequest;
use App\Models\Refund;
use App\Traits\ApiResponse;

class RefundController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $user = auth()->user();
        $query = Refund::with(['user', 'boardingHouse'])->latest();
        PengelolaScopeHelper::applyBoardingHouseIdFilter($query, $user, 'boarding_house_id');
        $refunds = $query->paginate(10);

        return $this->apiResponse($refunds, 'Data berhasil diambil.');
    }

    public function update(UpdateRefundRequest $request, Refund $refund)
    {
        $user = auth()->user();
        $ids = PengelolaScopeHelper::getBoardingHouseIds($user);
        if ($ids !== null && !in_array($refund->boarding_house_id, $ids)) {
            abort(403);
        }

        app(UpdateRefundAction::class)->execute($refund, $request->validated());

        return $this->apiResponse(null, 'Status pengembalian dana diperbarui.');
    }
}
