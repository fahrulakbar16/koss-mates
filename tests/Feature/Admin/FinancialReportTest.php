<?php

namespace Tests\Feature\Admin;

use App\Actions\Admin\FinancialReport\GetBoardingHouseRecap;
use App\Actions\Admin\FinancialReport\GetBoardingHouseDetailedReport;
use App\Models\BoardingHouse;
use App\Models\Room;
use App\Models\RoomPrice;
use App\Models\Transaction;
use App\Models\TransactionLog;
use App\Models\User;
use App\Models\UserRooms;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialReportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_boarding_house_recap_includes_both_payment_and_income_types()
    {
        $user = new User();
        $user->name = 'Test User';
        $user->email = 'test@example.com';
        $user->password = bcrypt('password');
        $user->save();

        $boardingHouse = new BoardingHouse();
        $boardingHouse->name = 'Test Kos';
        $boardingHouse->owner_id = $user->id;
        $boardingHouse->address = 'Test Address';
        $boardingHouse->save();

        $room = new Room();
        $room->boarding_house_id = $boardingHouse->id;
        $room->name = 'Room 101';
        $room->save();
        
        $roomPrice = new RoomPrice();
        $roomPrice->room_id = $room->id;
        $roomPrice->duration = 1;
        $roomPrice->price = 1000000;
        $roomPrice->save();
        
        $userRoom = UserRooms::create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'boarding_house_id' => $boardingHouse->id,
            'room_price_id' => $roomPrice->id,
            'status' => 'active',
        ]);

        // Create a 'payment' log
        $transaction1 = new Transaction();
        $transaction1->user_id = $user->id;
        $transaction1->room_id = $room->id;
        $transaction1->room_price_id = $roomPrice->id;
        $transaction1->user_room_id = $userRoom->id;
        $transaction1->total_price = 1000;
        $transaction1->status = 'completed';
        $transaction1->save();

        TransactionLog::create([
            'transaction_id' => $transaction1->id,
            'boarding_house_id' => $boardingHouse->id,
            'room_id' => $room->id,
            'amount' => 1000,
            'transaction_date' => now(),
            'type' => 'payment',
            'status' => 'completed',
        ]);

        // Create an 'income' log
        $transaction2 = new Transaction();
        $transaction2->user_id = $user->id;
        $transaction2->room_id = $room->id;
        $transaction2->room_price_id = $roomPrice->id;
        $transaction2->user_room_id = $userRoom->id;
        $transaction2->total_price = 500;
        $transaction2->status = 'completed';
        $transaction2->save();

        TransactionLog::create([
            'transaction_id' => $transaction2->id,
            'boarding_house_id' => $boardingHouse->id,
            'room_id' => $room->id,
            'amount' => 500,
            'transaction_date' => now(),
            'type' => 'income',
            'status' => 'completed',
        ]);

        $action = new GetBoardingHouseRecap();
        $result = $action->execute($boardingHouse, []);

        $this->assertEquals(1500, (float)$result['income']);
        $this->assertEquals(1500, (float)$result['tenants']->first()->paid_amount);
    }

    public function test_detailed_report_includes_both_payment_and_income_types()
    {
        $user = new User();
        $user->name = 'Test User';
        $user->email = 'test_owner@example.com';
        $user->password = bcrypt('password');
        $user->save();

        $boardingHouse = new BoardingHouse();
        $boardingHouse->name = 'Test Kos';
        $boardingHouse->owner_id = $user->id;
        $boardingHouse->address = 'Test Address';
        $boardingHouse->save();

        TransactionLog::create([
            'boarding_house_id' => $boardingHouse->id,
            'amount' => 1000,
            'transaction_date' => now(),
            'type' => 'payment',
            'status' => 'completed',
        ]);

        TransactionLog::create([
            'boarding_house_id' => $boardingHouse->id,
            'amount' => 500,
            'transaction_date' => now(),
            'type' => 'income',
            'status' => 'completed',
        ]);

        $action = new GetBoardingHouseDetailedReport();
        $result = $action->execute($boardingHouse, []);

        $this->assertEquals(1500, (float)$result['total_income']);
        $this->assertCount(2, $result['incomes']);
    }
}
