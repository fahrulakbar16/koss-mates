<?php

namespace Tests\Feature;

use App\Models\BoardingHouse;
use App\Models\payment;
use App\Models\Refund;
use App\Models\Room;
use App\Models\RoomPrice;
use App\Models\RoomTransfer;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserRooms;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransferFinancialTest extends TestCase
{
    use RefreshDatabase;

    private function fixture(): array
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
            'status' => 'checked_in',
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

        return compact('tenant', 'oldUserRoom', 'newUserRoom', 'oldRoom', 'newRoom', 'newRoomPrice');
    }

    private function transfer(array $data, int $credit): RoomTransfer
    {
        return RoomTransfer::create([
            'user_id' => $data['tenant']->id,
            'user_room_id' => $data['oldUserRoom']->id,
            'room_id' => $data['newRoom']->id,
            'room_price_id' => $data['newRoomPrice']->id,
            'sisa_pembayaran' => $credit,
            'kekurangan_pembayaran' => max(0, 1500000 - $credit),
            'pengembalian_dana' => max(0, $credit - 1500000),
            'reason' => 'Move', 'plan_date' => Carbon::today()->subDays(2),
            'status' => 'approved', 'is_process' => 0,
        ]);
    }

    public function test_overdue_shortage_uses_full_price_and_is_not_processed_twice(): void
    {
        $data = $this->fixture();
        $transfer = $this->transfer($data, 1000000);
        $this->artisan('app:handle-tranfer-request')->assertSuccessful();
        $this->artisan('app:handle-tranfer-request')->assertSuccessful();
        $this->assertSame(1, Transaction::count());
        $transaction = Transaction::first();
        $this->assertSame(1500000, $transaction->total_price);
        $this->assertSame('incomplete', $transaction->status);
        $this->assertEquals(1000000, $transaction->payments()->sum('amount'));
        $this->assertSame(Carbon::today()->subDays(2)->toDateString(), $transaction->jatuh_tempo->toDateString());
        $this->assertSame('checked_out', $data['oldUserRoom']->fresh()->status);
        $this->assertSame('checkin_open', $data['newUserRoom']->fresh()->status);
        $this->assertEquals(1, $transfer->fresh()->is_process);
    }

    public function test_exact_balance_creates_completed_bill_and_history(): void
    {
        $data = $this->fixture();
        $this->transfer($data, 1500000);
        $this->artisan('app:handle-tranfer-request')->assertSuccessful();
        $this->assertSame('completed', Transaction::first()->status);
        $this->assertSame(1, $data['newUserRoom']->rekapHistories()->count());
        $this->assertSame(0, Refund::count());
    }

    public function test_refund_records_only_excess_and_applies_price_as_payment(): void
    {
        $data = $this->fixture();
        $this->transfer($data, 1700000);
        $this->artisan('app:handle-tranfer-request')->assertSuccessful();
        $this->assertSame('completed', Transaction::first()->status);
        $this->assertEquals(1500000, payment::sum('amount'));
        $this->assertEquals(200000, Refund::first()->amount);
        $this->assertSame('pending', Refund::first()->status);
    }

    public function test_missing_destination_rolls_back_source_and_allows_retry(): void
    {
        $data = $this->fixture();
        $transfer = $this->transfer($data, 0);
        $data['newUserRoom']->update(['status' => 'checked_out']);
        $this->artisan('app:handle-tranfer-request')->assertFailed();
        $this->assertSame('checked_in', $data['oldUserRoom']->fresh()->status);
        $this->assertEquals(0, $transfer->fresh()->is_process);
        $this->assertSame(0, Transaction::count());
        $data['newUserRoom']->update(['status' => 'booked']);
        $this->artisan('app:handle-tranfer-request')->assertSuccessful();
        $this->assertSame('pending', Transaction::first()->status);
        $this->assertSame(0, payment::count());
    }

    public function test_inconsistent_approved_amounts_do_not_change_tenancies(): void
    {
        $data = $this->fixture();
        $transfer = $this->transfer($data, 1000000);
        $transfer->update(['kekurangan_pembayaran' => 100]);
        $this->artisan('app:handle-tranfer-request')->assertFailed();
        $this->assertSame(0, Transaction::count());
        $this->assertSame('checked_in', $data['oldUserRoom']->fresh()->status);
        $this->assertEquals(0, $transfer->fresh()->is_process);
    }

    public function test_financial_failure_rolls_back_statuses_and_payments(): void
    {
        $data = $this->fixture();
        $transfer = $this->transfer($data, 1700000);
        \Illuminate\Support\Facades\Schema::drop('refunds');
        $this->artisan('app:handle-tranfer-request')->assertFailed();
        $this->assertSame(0, Transaction::count());
        $this->assertSame(0, payment::count());
        $this->assertSame('checked_in', $data['oldUserRoom']->fresh()->status);
        $this->assertSame('booked', $data['newUserRoom']->fresh()->status);
        $this->assertEquals(0, $transfer->fresh()->is_process);
    }

    public function test_future_and_unapproved_transfers_are_not_processed(): void
    {
        $data = $this->fixture();
        $transfer = $this->transfer($data, 0);
        $transfer->update(['plan_date' => Carbon::tomorrow()]);
        $this->artisan('app:handle-tranfer-request')->assertSuccessful();
        $this->assertSame(0, Transaction::count());
        $transfer->update(['plan_date' => Carbon::yesterday(), 'status' => 'pending']);
        $this->artisan('app:handle-tranfer-request')->assertSuccessful();
        $this->assertSame(0, Transaction::count());
    }
}
