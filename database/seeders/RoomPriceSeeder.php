<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomPrice;
use Illuminate\Database\Seeder;

class RoomPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = Room::all();

        if ($rooms->isEmpty()) {
            $this->command->warn('Rooms not found. Please run RoomSeeder first.');
            return;
        }

        foreach ($rooms as $room) {
            // Determine base price based on room name (type)
            $basePrice = $this->getBasePrice($room->name);
            
            // Create prices for different durations: 1, 3, 6, 12 months
            $durations = [1, 3, 6, 12];
            $discounts = [
                1 => 0,      // No discount for 1 month
                3 => 0.05,   // 5% discount for 3 months
                6 => 0.10,   // 10% discount for 6 months
                12 => 0.15,  // 15% discount for 12 months
            ];

            foreach ($durations as $duration) {
                $discount = $discounts[$duration];
                $price = $basePrice * (1 - $discount) * $duration;
                
                // Round to nearest 50000
                $price = round($price / 50000) * 50000;

                RoomPrice::firstOrCreate(
                    [
                        'room_id' => $room->id,
                        'duration' => $duration,
                    ],
                    [
                        'price' => $price,
                    ]
                );
            }
        }
    }

    /**
     * Get base price per month based on room type
     */
    private function getBasePrice(string $roomName): float
    {
        if (stripos($roomName, 'Premium') !== false) {
            // Premium: 2.000.000 - 3.500.000 per month
            return rand(2000000, 3500000);
        } elseif (stripos($roomName, 'Standar') !== false) {
            // Standar: 1.200.000 - 2.000.000 per month
            return rand(1200000, 2000000);
        } else {
            // Ekonomi: 600.000 - 1.200.000 per month
            return rand(600000, 1200000);
        }
    }
}
