<?php

namespace App\Actions\Calendar;

use App\Models\BoardingHouse;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserRooms;

class GetCalendarEventsAction
{
    /**
     * Get check-in/check-out calendar events for a cluster within a date range.
     *
     * @return array|null Null when the cluster is outside the user's scope.
     */
    public function execute(int $clusterId, string $start, string $end, User $user): ?array
    {
        $isPemilik = $user->hasRole('Pemilik');
        $isPengelola = $user->hasRole('Pengelola');

        $boardingHouseQuery = BoardingHouse::query()->where('cluster_id', $clusterId);

        if ($isPemilik) {
            $boardingHouseQuery->where('owner_id', $user->id);
        } elseif ($isPengelola) {
            $boardingHouseQuery->where('pengelola_id', $user->id);
        }

        $boardingHouseIds = $boardingHouseQuery->pluck('id');

        if ($boardingHouseIds->isEmpty()) {
            return null;
        }

        $events = [];

        // Check-in aktual
        $checkins = UserRooms::whereNotNull('start_date')
            ->whereBetween('start_date', [$start, $end])
            ->whereHas('room', function ($q) use ($boardingHouseIds) {
                $q->whereIn('boarding_house_id', $boardingHouseIds);
            })
            ->with(['user', 'room.boardingHouse'])
            ->get();

        foreach ($checkins as $userRoom) {
            $events[] = [
                'id' => 'checkin-' . $userRoom->id,
                'title' => 'Check-in: ' . ($userRoom->user->name ?? 'N/A'),
                'start' => \Carbon\Carbon::parse($userRoom->start_date)->toDateString(),
                'allDay' => true,
                'color' => '#22c55e',
                'extendedProps' => [
                    'type' => 'checkin',
                    'tenant' => $userRoom->user->name ?? 'N/A',
                    'room' => $userRoom->room->name ?? '-',
                    'boarding_house' => $userRoom->room->boardingHouse->name ?? '-',
                    'status' => $userRoom->status,
                ],
            ];
        }

        // Check-out aktual
        $checkouts = UserRooms::whereNotNull('end_date')
            ->whereBetween('end_date', [$start, $end])
            ->whereHas('room', function ($q) use ($boardingHouseIds) {
                $q->whereIn('boarding_house_id', $boardingHouseIds);
            })
            ->with(['user', 'room.boardingHouse'])
            ->get();

        foreach ($checkouts as $userRoom) {
            $events[] = [
                'id' => 'checkout-' . $userRoom->id,
                'title' => 'Check-out: ' . ($userRoom->user->name ?? 'N/A'),
                'start' => \Carbon\Carbon::parse($userRoom->end_date)->toDateString(),
                'allDay' => true,
                'color' => '#ef4444',
                'extendedProps' => [
                    'type' => 'checkout',
                    'tenant' => $userRoom->user->name ?? 'N/A',
                    'room' => $userRoom->room->name ?? '-',
                    'boarding_house' => $userRoom->room->boardingHouse->name ?? '-',
                    'status' => $userRoom->status,
                ],
            ];
        }

        // Rencana booking (belum check-in)
        $plannedBookings = Transaction::where('type', Transaction::TYPE_BOOKED)
            ->whereIn('status', [Transaction::STATUS_PENDING, Transaction::STATUS_COMPLETED])
            ->whereNotNull('planned_checkin_date')
            ->whereBetween('planned_checkin_date', [$start, $end])
            ->whereHas('room', function ($q) use ($boardingHouseIds) {
                $q->whereIn('boarding_house_id', $boardingHouseIds);
            })
            ->whereHas('userRoom', function ($q) {
                $q->where('status', '!=', 'checked_in');
            })
            ->with(['user', 'room.boardingHouse'])
            ->get();

        foreach ($plannedBookings as $transaction) {
            $events[] = [
                'id' => 'planned-' . $transaction->id,
                'title' => 'Rencana Check-in: ' . ($transaction->user->name ?? 'N/A'),
                'start' => $transaction->planned_checkin_date->toDateString(),
                'allDay' => true,
                'color' => '#f59e0b',
                'extendedProps' => [
                    'type' => 'planned',
                    'tenant' => $transaction->user->name ?? 'N/A',
                    'room' => $transaction->room->name ?? '-',
                    'boarding_house' => $transaction->room->boardingHouse->name ?? '-',
                    'status' => $transaction->status,
                ],
            ];
        }

        return $events;
    }
}
