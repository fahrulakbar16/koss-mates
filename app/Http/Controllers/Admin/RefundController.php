<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use App\Http\Requests\Admin\UpdateRefundRequest; // Import Request
use App\Actions\Refund\UpdateRefundAction; // Import Action
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class RefundController extends Controller
{
    public function index()
    {
        $refunds = Refund::with(['user', 'boardingHouse'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Admin/Refunds/Index', [
            'refunds' => $refunds
        ]);
    }

    public function update(UpdateRefundRequest $request, Refund $refund)
    {
        app(UpdateRefundAction::class)->execute($refund, $request->validated());

        return redirect()->back()->with('success', 'Status pengembalian dana diperbarui.');
    }
}
