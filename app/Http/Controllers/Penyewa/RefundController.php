<?php

namespace App\Http\Controllers\Penyewa;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Refund;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function Symfony\Component\Clock\now;

class RefundController extends Controller
{
    public function index()
    {
        $refunds = Refund::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return Inertia::render('Penyewa/Refunds/Index', [
            'refunds' => $refunds,
        ]);
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

        DB::beginTransaction();
        try {
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

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new CustomException("Gagal mengkonfirmasi pengembalian dana: " . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Pengembalian dana telah dikonfirmasi diterima.');
    }
}
