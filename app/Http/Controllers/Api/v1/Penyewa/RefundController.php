<?php

namespace App\Http\Controllers\Api\v1\Penyewa;

use App\Actions\Refund\UpdateRefundAction;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateRefundRequest;
use App\Models\Expense;
use App\Models\Refund;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefundController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $refunds = Refund::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return $this->apiResponse($refunds, 'Data berhasil diambil.');
    }

    public function confirm($refundId)
    {
        $refund = Refund::where('id', $refundId)->first();

        if (!$refund) {
            throw new CustomException("Data tidak ditemukan.");
        }

        if ($refund->status !== 'menunggu konfirmasi') {
            throw new CustomException("Status pengembalian dana tidak valid untuk konfirmasi.");
        }

        $refund->update([
            'status' => 'selesai',
        ]);


        Expense::create([
            'user_id' => Auth::id(),
            'boarding_house_id' => $refund->boarding_house_id,
            'room_id' => $refund->room_id,
            'expense_date' => now(),
            'amount' => $refund->amount,
            'description' => "Pengembalian dana - pindah kamar",
            'category' => 'pengeluaran',
            'status' => 'selesai',
        ]);


        return $this->apiResponse(null, 'Pengembalian dana telah dikonfirmasi diterima.');
    }
}
