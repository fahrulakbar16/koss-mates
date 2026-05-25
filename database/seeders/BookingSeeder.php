<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Room;
use App\Models\RoomPrice;
use App\Models\UserRooms;
use App\Models\payment;
use App\Models\RekapHistory;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Get tenants
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'Penyewa');
        })->get();

        // Get available rooms and shuffle them to get random unique rooms
        $rooms = Room::where('status', 'available')->get();
        if ($rooms->count() < 5) {
            $rooms = Room::all();
        }
        $rooms = $rooms->shuffle();

        $roomPrices = RoomPrice::all();

        if ($users->isEmpty() || $rooms->isEmpty() || $roomPrices->isEmpty()) {
            return;
        }

        foreach ($users as $index => $user) {
            if ($index >= 5) break; // Limit to 5 dummy bookings

            $room = $rooms->pop();
            if (!$room) break;

            $roomPrice = $roomPrices->where('room_id', $room->id)->first() ?? $roomPrices->random();

            // Update room status
            $room->update(['status' => 'occupied']);

            // Create UserRoom
            $userRoom = UserRooms::create([
                'user_id' => $user->id,
                'boarding_house_id' => $room->boarding_house_id,
                'room_id' => $room->id,
                'room_price_id' => $roomPrice->id,
                'status' => 'active',
                'start_date' => now(),
                'end_date' => now()->addMonths(1),
                'verifikasi_admin' => 1,
                'planned_checkin_date' => now(),
            ]);

            // Create Transaction
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'user_room_id' => $userRoom->id,
                'room_id' => $room->id,
                'room_price_id' => $roomPrice->id,
                'total_price' => $roomPrice->price ?? 1000000,
                'payment_scheme' => 'full',
                'type' => 'booked',
                'status' => 'completed',
                'transaction_code' => 'TRX-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5)),
                'jatuh_tempo' => now()->addMonth(),
                'planned_checkin_date' => now(),
            ]);

            // Create Payment
            payment::create([
                'transaction_id' => $transaction->id,
                'payment_sequence' => 1,
                'amount' => $transaction->total_price,
                'payment_method' => 'gateway',
                'payment_status' => 'success',
                'payment_date' => now(),
            ]);

            // Create RekapHistory
            RekapHistory::create([
                'user_room_id' => $userRoom->id,
                'month' => now()->month,
                'year' => now()->year,
                'total_price' => $transaction->total_price,
                'total_payment' => $transaction->total_price,
                'payment_date' => now(),
                'status' => 'lunas',
            ]);
        }
    }
}
