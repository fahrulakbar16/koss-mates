<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PengelolaScopeHelper;
use App\Http\Controllers\Controller;
use App\Models\Refund;
use App\Http\Requests\Admin\UpdateRefundRequest;
use App\Actions\Refund\UpdateRefundAction;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RefundController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Refund::with(['user', 'boardingHouse'])->latest();
        PengelolaScopeHelper::applyBoardingHouseIdFilter($query, $user, 'boarding_house_id');
        $refunds = $query->paginate(10);

        return Inertia::render('Admin/Refunds/Index', [
            'refunds' => $refunds
        ]);
    }

    public function update(UpdateRefundRequest $request, Refund $refund)
    {
        $user = Auth::user();
        $ids = PengelolaScopeHelper::getBoardingHouseIds($user);
        if ($ids !== null && !in_array($refund->boarding_house_id, $ids)) {
            abort(403);
        }

        app(UpdateRefundAction::class)->execute($refund, $request->validated());

        return redirect()->back()->with('success', 'Status pengembalian dana diperbarui.');
    }
}
