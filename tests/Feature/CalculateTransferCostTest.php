<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Room;
use App\Models\UserRooms;
use App\Models\RoomPrice;
use App\Models\BoardingHouse;
use App\Models\RekapHistory;
use App\Actions\RoomTransfer\CalculateRoomTransferCostAction;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class CalculateTransferCostTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculate_cost_with_rekap_history()
    {
        // 1. Setup Data
        $user = User::factory()->create();
        Auth::login($user);

        // Mock Boarding House (needed for room creation usually, or nullable but good to have)
        // Using factory if available, else manual create
        $boardingHouse = BoardingHouse::create([
            'owner_id' => $user->id,
            'name' => 'Kos Test',
            'status' => 'active',
            'latitude' => 0,
            'longitude' => 0,
        ]);

        $oldRoom = Room::create([
            'boarding_house_id' => $boardingHouse->id,
            'name' => 'Old Room',
            'price' => 1000000,
            'status' => 'occupied'
        ]);

        $oldPrice = RoomPrice::create([
            'room_id' => $oldRoom->id,
            'duration' => 1,
            'price' => 1000000
        ]);

        $newRoom = Room::create([
            'boarding_house_id' => $boardingHouse->id,
            'name' => 'New Room',
            'price' => 1500000,
            'status' => 'available'
        ]);

        $newPrice = RoomPrice::create([
            'room_id' => $newRoom->id,
            'duration' => 1,
            'price' => 1500000
        ]);

        $userRoom = UserRooms::create([
            'user_id' => $user->id,
            'boarding_house_id' => $boardingHouse->id,
            'room_id' => $oldRoom->id,
            'room_price_id' => $oldPrice->id,
            'status' => 'checkin_in',
            'start_date' => Carbon::now()->subMonths(3), // started 3 months ago
            'end_date' => Carbon::now()->addMonths(3), // ends in 3 months
        ]);

        // Plan Date: Next Month 1st
        $planDate = Carbon::now()->addMonth()->startOfMonth();
        // Example: If now is Feb, Plan = Mar 1st.

        // Add Rekap History
        // Case 1: Paid for current month (Feb) - Should NOT count (Feb < Mar)
        // Case 2: Paid for Plan Month (Mar) - Should COUNT
        // Case 3: Paid for Next Month (Apr) - Should COUNT

        // Month argument for RekapHistory might be int 1-12
        // Let's create history for Plan Date Month
        RekapHistory::create([
            'user_room_id' => $userRoom->id,
            'month' => $planDate->month,
            'year' => $planDate->year,
            'total_price' => 1000000,
            'total_payment' => 1000000,
            'status' => 'lunas'
        ]);

        // Create history for Plan Date Month + 1
        $nextMonth = $planDate->copy()->addMonth();
        RekapHistory::create([
            'user_room_id' => $userRoom->id,
            'month' => $nextMonth->month,
            'year' => $nextMonth->year,
            'total_price' => 1000000,
            'total_payment' => 1000000,
            'status' => 'lunas'
        ]);

        // Create history for Past Month (Plan Date - 1) - Should NOT count
        $prevMonth = $planDate->copy()->subMonth();
        RekapHistory::create([
            'user_room_id' => $userRoom->id,
            'month' => $prevMonth->month,
            'year' => $prevMonth->year,
            'total_price' => 1000000,
            'total_payment' => 1000000,
            'status' => 'lunas'
        ]);

        // 2. Execute Action
        $action = new CalculateRoomTransferCostAction();
        $result = $action->execute([
            'user_room_id' => $userRoom->id,
            'room_price_id' => $newPrice->id,
            'plan_date' => $planDate->format('Y-m-d'),
        ]);

        // 3. Verify Result
        // Expected Sisa: 2 months * 1,000,000 = 2,000,000
        // New Price: 1,500,000
        // Diff: 1,500,000 - 2,000,000 = -500,000
        // Kekurangan: 0
        // Pengembalian: 500,000

        $this->assertEquals(2000000, $result['sisa_pembayaran'], 'Sisa pembayaran calculation wrong');
        $this->assertEquals(0, $result['kekurangan_pembayaran'], 'Kekurangan pembayaran calculation wrong');
        $this->assertEquals(500000, $result['pengembalian_dana'], 'Pengembalian dana calculation wrong');
    }
}
