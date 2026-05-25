<?php

namespace App\Actions\Room;

use App\Models\BoardingHouse;
use App\Models\Room;
use Illuminate\Support\Facades\DB;

class BatchStoreRoomAction
{
    public function execute(BoardingHouse $boardingHouse, array $data): void
    {
        DB::transaction(function () use ($boardingHouse, $data) {
            $count = $data['count'];
            $startNumber = $data['start_number'] ?? null;

            if ($startNumber === null) {
                $lastRoom = Room::where('boarding_house_id', $boardingHouse->id)
                    ->when(config('database.default') === 'sqlite', function ($query) {
                        return $query->orderByRaw('CAST(number AS INTEGER) DESC');
                    }, function ($query) {
                        return $query->orderByRaw('CAST(number AS UNSIGNED) DESC');
                    })
                    ->first();
                $startNumber = $lastRoom ? (int)$lastRoom->number + 1 : 1;
            }

            // Uniqueness check for the range
            $endNumber = $startNumber + $count - 1;
            $existingNumbers = Room::where('boarding_house_id', $boardingHouse->id)
                ->whereBetween('number', [$startNumber, $endNumber])
                ->pluck('number')
                ->toArray();

            if (!empty($existingNumbers)) {
                $numbersString = implode(', ', $existingNumbers);
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'start_number' => ["Nomor kamar berikut sudah dipakai: {$numbersString}. Silakan gunakan nomor awal yang berbeda."],
                ]);
            }

            for ($i = 0; $i < $count; $i++) {
                $currentNumber = $startNumber + $i;

                $roomData = [
                    'boarding_house_id' => $boardingHouse->id,
                    'number' => $currentNumber,
                    'capacity' => $data['capacity'] ?? 1,
                    'description' => $data['description'] ?? null,
                    'status' => 'available',
                    'facilities' => $data['facilities'] ?? null,
                ];

                $room = app(StoreRoomAction::class)->execute($roomData);

                if (!empty($data['prices'])) {
                    app(StoreRoomPricesAction::class)->execute($room, $data['prices']);
                }
            }
        });
    }
}
