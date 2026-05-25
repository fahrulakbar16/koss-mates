<?php

namespace App\Actions\Rekap;

use App\Models\BoardingHouse;

class GetRecapPayment
{
    public function execute($boardingHouseId)
    {
        // Fetch all user_rooms (tenants) for this boarding house (include all statuses)
        $userRooms = \App\Models\UserRooms::with(['user', 'room', 'rekapHistories'])
            ->where('boarding_house_id', $boardingHouseId)
            ->get();


        $currentYear = date('Y');
        $currentMonth = 12; // 1-12

        $monthNames = [
            1 => 'januari',
            2 => 'februari',
            3 => 'maret',
            4 => 'april',
            5 => 'mei',
            6 => 'juni',
            7 => 'juli',
            8 => 'agustus',
            9 => 'september',
            10 => 'oktober',
            11 => 'november',
            12 => 'desember'
        ];

        $result = [];

        foreach ($userRooms as $userRoom) {
            $moveInDate = $userRoom->created_at;
            $moveInMonth = (int) $moveInDate->format('n');
            $moveInYear = (int) $moveInDate->format('Y');

            // Check if tenant has checked out
            $hasCheckedOut = in_array($userRoom->status, ['checked_out', 'cancelled']);

            // If checked out, get the checkout date (use updated_at as approximation)
            $checkoutMonth = null;
            $checkoutYear = null;
            if ($hasCheckedOut) {
                $checkoutMonth = (int) $userRoom->updated_at->format('n');
                $checkoutYear = (int) $userRoom->updated_at->format('Y');
            }

            $pembayaran = [];

            // Loop from January to current month
            for ($month = 1; $month <= $currentMonth; $month++) {
                $monthName = $monthNames[$month];

                // If this month is before move-in month (in the same year), show "belum masuk"
                if ($currentYear == $moveInYear && $month < $moveInMonth) {
                    $pembayaran[] = [$monthName => 'belum masuk'];
                    continue;
                }

                // If tenant has checked out and this month is after checkout, show "sudah keluar"
                if ($hasCheckedOut && $currentYear == $checkoutYear && $month > $checkoutMonth) {
                    $pembayaran[] = [$monthName => 'sudah keluar'];
                    continue;
                }

                // Check if there's a rekap history for this month
                $rekapHistory = $userRoom->rekapHistories()
                    ->where('year', $currentYear)
                    ->where('month', $month)
                    ->first();

                if ($rekapHistory) {
                    // Use rekap history status
                    if ($rekapHistory->status === 'completed') {
                        $pembayaran[] = [$monthName => 'lunas'];
                    } else {
                        $pembayaran[] = [$monthName => 'belum lunas'];
                    }
                } else {
                    // No rekap history for this month
                    $pembayaran[] = [$monthName => 'belum lunas'];
                }
            }

            $result[] = [
                'id' => $userRoom->id,
                'nama' => $userRoom->user->name ?? 'N/A',
                'nama_room' => $userRoom->room->name ?? 'N/A',
                'status' => $userRoom->status,
                'pembayaran' => $pembayaran
            ];
        }



        return $result;
    }
}
