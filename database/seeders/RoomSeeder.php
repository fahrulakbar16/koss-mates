<?php

namespace Database\Seeders;

use App\Models\BoardingHouse;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $boardingHouses = BoardingHouse::all();

        if ($boardingHouses->isEmpty()) {
            $this->command->warn('Boarding houses not found. Please run BoardingHouseSeeder first.');
            return;
        }

        $roomFacilities = [
            // Basic facilities
            ['AC', 'Kamar Mandi Dalam', 'WiFi', 'Listrik'],
            ['AC', 'Kamar Mandi Dalam', 'WiFi', 'Listrik', 'Lemari'],
            ['AC', 'Kamar Mandi Dalam', 'WiFi', 'Listrik', 'Lemari', 'Kasur'],
            // Premium facilities
            ['AC', 'Kamar Mandi Dalam', 'WiFi', 'Listrik', 'Lemari', 'Kasur', 'TV', 'Kulkas', 'Meja Belajar'],
            // Economy facilities
            ['Kamar Mandi Dalam', 'WiFi', 'Listrik', 'Lemari'],
            ['Kamar Mandi Luar', 'WiFi', 'Listrik'],
        ];

        foreach ($boardingHouses as $boardingHouse) {
            // Generate 5-10 rooms per boarding house
            $numberOfRooms = rand(5, 10);

            for ($i = 1; $i <= $numberOfRooms; $i++) {
                $roomType = $this->getRoomType($i, $numberOfRooms);
                $facilities = $roomFacilities[array_rand($roomFacilities)];
                $capacity = rand(1, 2);
                $statusOptions = ['available']; // 75% available
                $status = $statusOptions[array_rand($statusOptions)];

                $room = Room::firstOrCreate(
                    [
                        'boarding_house_id' => $boardingHouse->id,
                        'number' => str_pad($i, 2, '0', STR_PAD_LEFT),
                    ],
                    [
                        'name' => "Kamar {$roomType} No. " . str_pad($i, 2, '0', STR_PAD_LEFT),
                        'description' => $this->getRoomDescription($roomType, $facilities),
                        'capacity' => $capacity,
                        'status' => $status,
                        'facilities' => $facilities,
                    ]
                );
            }
        }
    }

    /**
     * Get room type based on room number
     */
    private function getRoomType(int $roomNumber, int $totalRooms): string
    {
        $percentage = ($roomNumber / $totalRooms) * 100;

        if ($percentage <= 20) {
            return 'Premium';
        } elseif ($percentage <= 50) {
            return 'Standar';
        } else {
            return 'Ekonomi';
        }
    }

    /**
     * Get room description based on type and facilities
     */
    private function getRoomDescription(string $roomType, array $facilities): string
    {
        $descriptions = [
            'Premium' => 'Kamar premium dengan fasilitas lengkap dan luas. Cocok untuk Anda yang mengutamakan kenyamanan.',
            'Standar' => 'Kamar standar dengan fasilitas memadai. Pilihan tepat untuk keseimbangan antara kenyamanan dan harga.',
            'Ekonomi' => 'Kamar ekonomis dengan fasilitas dasar. Cocok untuk budget terbatas namun tetap nyaman.',
        ];

        $baseDescription = $descriptions[$roomType] ?? $descriptions['Standar'];

        $facilityText = implode(', ', array_slice($facilities, 0, 3));
        if (count($facilities) > 3) {
            $facilityText .= ', dan lainnya';
        }

        return $baseDescription . ' Fasilitas: ' . $facilityText . '.';
    }
}
