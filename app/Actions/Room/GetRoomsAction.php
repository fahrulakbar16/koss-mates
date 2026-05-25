<?php

namespace App\Actions\Room;

use App\Models\BoardingHouse;
use Illuminate\Http\Request;

class GetRoomsAction
{
    /**
     * Get paginated rooms with filters.
     */
    public function execute(Request $request, BoardingHouse $boardingHouse): array
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $perPage = $request->input('per_page', 10);

        $query = $boardingHouse->rooms()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('number', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->with(['prices', 'penyewaAktif'])
            ->withCount('transactions')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');

        $rooms = $query->paginate($perPage)
            ->appends($request->query());

        // Add min_price and update status based on active tenant
        $rooms->getCollection()->transform(function ($room) {
            $room->min_price = $room->getMinPrice();

            // Override status to 'occupied' if there's an active tenant
            if ($room->active_tenant) {
                $room->status = 'occupied';
            }

            return $room;
        });

        return [
            'rooms' => $rooms,
        ];
    }
}
