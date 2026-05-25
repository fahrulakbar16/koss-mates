<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Room;
use App\Models\UserRooms;
use App\Models\RoomTransfer;
use App\Models\RoomPrice;
use App\Models\BoardingHouse;
use App\Models\Transaction;
use App\Models\payment;
use App\Models\Refund;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransferFinancialTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_transfer_financials_shortage_and_refund()
    {
        // 1. Setup Data
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner@example.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
            'username' => 'owner',
        ]);

        $boardingHouse = BoardingHouse::create([
            'owner_id' => $owner->id,
            'name' => 'Kos Kita',
            'address' => 'Jl. Test',
            'phone' => '08123456789',
            'status' => 'active',
            // Fill other required fields with dummy data if needed
            'thumbnail' => 'default.jpg',
            'description' => 'Test kos',
            'latitude' => 0,
            'longitude' => 0,
        ]);

        $tenant = User::create([
            'name' => 'Tenant',
            'email' => 'tenant@example.com',
            'password' => bcrypt('password'),
            'role' => 'tenant',
            'username' => 'tenant',
        ]);

        // Old Room
        $oldRoom = Room::create([
            'boarding_house_id' => $boardingHouse->id,
            'name' => 'Room 101',
            'number' => '101',
            'status' => 'occupied',
            'capacity' => 1,
        ]);

        $oldRoomPrice = RoomPrice::create([
            'room_id' => $oldRoom->id,
            'duration' => 1,
            'price' => 1000000,
        ]);

        // New Room
        $newRoom = Room::create([
            'boarding_house_id' => $boardingHouse->id,
            'name' => 'Room 102',
            'number' => '102',
            'status' => 'booked',
            'capacity' => 1,
        ]);

        $newRoomPrice = RoomPrice::create([
            'room_id' => $newRoom->id,
            'duration' => 1,
            'price' => 1500000,
        ]);

        // Old UserRoom (active)
        $oldUserRoom = UserRooms::create([
            'user_id' => $tenant->id,
            'boarding_house_id' => $boardingHouse->id,
            'room_id' => $oldRoom->id,
            'room_price_id' => $oldRoomPrice->id,
            'status' => 'checkin_in',
            'start_date' => Carbon::now()->subMonth(),
            'end_date' => Carbon::now()->addMonth(),
        ]);

        // New UserRoom (booked) - Logic requires this to exist
        $newUserRoom = UserRooms::create([
            'user_id' => $tenant->id,
            'boarding_house_id' => $boardingHouse->id,
            'room_id' => $newRoom->id,
            'room_price_id' => $newRoomPrice->id,
            'status' => 'booked',
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonth(),
        ]);

        // Transfer Request - Shortage Case
        $transferShortage = RoomTransfer::create([
            'user_id' => $tenant->id,
            'user_room_id' => $oldUserRoom->id,
            'room_id' => $newRoom->id,
            'room_price_id' => $newRoomPrice->id,
            'kekurangan_pembayaran' => 500000,
            'sisa_pembayaran' => 200000,
            'pengembalian_dana' => 0,
            'reason' => 'Upgrade',
            'plan_date' => Carbon::today(),
            'status' => 'approved',
        ]);

        // 2. Run Command
        Artisan::call('app:handle-tranfer-request');

        // 3. Verify Shortage Logic
        $this->assertDatabaseHas('transactions', [
            'user_id' => $tenant->id,
            'room_id' => $newRoom->id,
            'total_price' => 500000,
            'payment_scheme' => 'installment',
            'type' => 'booked',
            'status' => Transaction::STATUS_PENDING,
        ]);

        // Get the transaction to check ID for payment
        $transaction = Transaction::where('user_id', $tenant->id)
            ->where('room_id', $newRoom->id)
            ->where('total_price', 500000)
            ->first();

        $this->assertNotNull($transaction);

        $this->assertDatabaseHas('payments', [
            'transaction_id' => $transaction->id,
            'amount' => 200000,
            'payment_method' => 'cash',
        ]);

        // Verify Status Updates (from original command logic)
        $this->assertDatabaseHas('user_rooms', [
            'id' => $newUserRoom->id,
            'status' => 'checkin_open',
        ]);

        $this->assertDatabaseHas('user_rooms', [
            'id' => $oldUserRoom->id,
            'status' => 'checked_out',
        ]);

        $this->assertDatabaseHas('rooms', [
            'id' => $oldRoom->id,
            'status' => 'available',
        ]);

        // --- Test Refund Case ---
        // Clean up or reuse? logic handles loops.
        // Let's create another transfer for refund.

        $tenant2 = User::create([
            'name' => 'Tenant 2',
            'email' => 'tenant2@example.com',
            'password' => bcrypt('password'),
            'role' => 'tenant',
            'username' => 'tenant2',
        ]);

        // Create valid user room for tenant 2
        $userRoom2 = UserRooms::create([
            'user_id' => $tenant2->id,
            'boarding_house_id' => $boardingHouse->id,
            'room_id' => $oldRoom->id, // Just linking content, logically fine for test
            'room_price_id' => $oldRoomPrice->id,
            'status' => 'checkin_in',
        ]);

        $transferRefund = RoomTransfer::create([
            'user_id' => $tenant2->id,
            'user_room_id' => $userRoom2->id,
            'room_id' => $oldRoom->id, // reusing available room
            'room_price_id' => $oldRoomPrice->id,
            'kekurangan_pembayaran' => 0,
            'sisa_pembayaran' => 0,
            'pengembalian_dana' => 150000,
            'reason' => 'Downgrade',
            'plan_date' => Carbon::today(),
            'status' => 'approved',
        ]);

        // Mock new UserRoom for tenant 2 to avoid "if ($newUserRoom)" block doing nothing but also not failing
        UserRooms::create([
            'user_id' => $tenant2->id,
            'boarding_house_id' => $boardingHouse->id,
            'room_id' => $oldRoom->id,
            'status' => 'booked',
        ]);

        // Run Command Again
        Artisan::call('app:handle-tranfer-request');

        $this->assertDatabaseHas('refunds', [
            'user_id' => $tenant2->id,
            'amount' => 150000,
            'status' => 'pending',
            'is_verified' => false,
        ]);
    }
}
