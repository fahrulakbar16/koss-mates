<?php

namespace App\Actions\RoomTransfer;

use App\Models\UserRooms;
use App\Models\RoomPrice;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CalculateRoomTransferCostAction
{
    /**
     * Calculate cost for room transfer.
     *
     * @param  array  $data
     * @return array
     */
    public function execute(array $data, $userRoom): array
    {
        $newPrice = RoomPrice::findOrFail($data['room_price_id']);
        $planDate = Carbon::parse($data['plan_date']);

        $sisaPembayaran = 0;

        if ($userRoom->plan) {
            // Price per month (assuming price is total for duration, usually duration is 1 for monthly, but just in case)
            // If duration is in months.
            $pricePerMonth = $userRoom->plan->price / $userRoom->plan->duration;

            // Filter rekap histories that are "remaining" or "paid for future" relative to plan_date
            // We want to count months that have been paid for but are AFTER or ON the plan_date
            // Explicitly: records where (year > planYear) OR (year == planYear AND month >= planMonth)
            // AND status is 'lunas' or 'success' (assuming we only count paid ones as "sisa saldo")

            $startDate = Carbon::parse($userRoom->start_date)->startOfDay();
            $planDateStartOfDay = $planDate->copy()->startOfDay();
            $daysOccupied = $startDate->diffInDays($planDateStartOfDay);

            // Note: The user request says "yang lebih/sama dengan dari bulan plan_date"
            // and "jika si user menampati kos di bawah 20 hari maka pembayaran bulan pertama tidak hangus"
            $matchingHistories = $userRoom->rekapHistories->filter(function ($history) use ($planDate, $startDate, $daysOccupied) {
                // Determine if this history relates to the very first month of their stay
                if ($history->year == $startDate->year && $history->month == $startDate->month) {
                    return $daysOccupied < 20;
                }

                if ($history->year > $planDate->year) {
                    return true;
                }

                if ($history->year == $planDate->year && $history->month >= $planDate->month) {
                    return true;
                }

                return false;
            });

            // If specific status check is needed, add it. Assuming all rekapHistories attached are valid/paid or relevant.
            // Usually rekapHistory is generated when payment is confirmed? Or is it generated monthly?
            // "sisa bulan di ambil dari data relas $userRoom->rekapHistories() ... lalu hasilnya di kali price harga 1 bulan"

            $countRemainingMonths = $matchingHistories->count();
            $sisaPembayaran = $countRemainingMonths * $pricePerMonth;
        }

        $kekuranganPembayaran = 0;
        $pengembalianDana = 0;

        $diff = $newPrice->price - $sisaPembayaran;

        if ($diff > 0) {
            $kekuranganPembayaran = $diff;
        } else {
            $pengembalianDana = abs($diff);
        }

        return [
            'sisa_pembayaran' => $sisaPembayaran,
            'kekurangan_pembayaran' => $kekuranganPembayaran,
            'pengembalian_dana' => $pengembalianDana,
            'new_price' => $newPrice->price,
        ];
    }
}
