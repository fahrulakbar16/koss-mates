<?php

namespace Tests\Feature;

use App\Actions\Room\CheckoutAction;
use App\Models\BoardingHouse;
use App\Models\Room;
use App\Models\UserRooms;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RoomCheckoutTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
        });
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->timestamps();
        });
        Schema::create('user_rooms', function (Blueprint $table) {
            $table->id();
            $table->integer('room_id');
            $table->string('status');
            $table->dateTime('end_date')->nullable();
            $table->timestamps();
        });
        (require database_path('migrations/2026_02_08_213404_create_log_activities_table.php'))->up();
    }

    public static function activeTenancies(): array
    {
        return [
            ['available', 'checked_in'],
            ['booked', 'checked_in'],
            ['available', 'checkin_open'],
            ['occupied', 'checkin_open'],
            ['occupied', 'checked_in'],
        ];
    }

    #[DataProvider('activeTenancies')]
    public function test_checkout_closes_active_tenancy_even_when_room_status_is_stale(string $roomStatus, string $tenantStatus): void
    {
        $room = Room::create(['status' => $roomStatus]);
        $tenancy = UserRooms::create(['room_id' => $room->id, 'status' => $tenantStatus]);
        $history = UserRooms::create(['room_id' => $room->id, 'status' => 'checked_out', 'end_date' => '2025-01-01 00:00:00']);

        app(CheckoutAction::class)->execute(new BoardingHouse, $room);

        $this->assertSame('available', $room->fresh()->status);
        $this->assertSame('checked_out', $tenancy->fresh()->status);
        $this->assertNotNull($tenancy->fresh()->end_date);
        $this->assertSame('2025-01-01 00:00:00', $history->fresh()->end_date);
        $this->assertDatabaseCount('log_activities', 1);

        // A repeated checkout must not succeed using the stale model instance.
        $this->expectExceptionMessage('Kamar tidak dalam status terisi (occupied)');
        app(CheckoutAction::class)->execute(new BoardingHouse, $room);
    }

    public function test_booking_alone_cannot_be_checked_out(): void
    {
        $room = Room::create(['status' => 'booked']);
        $tenancy = UserRooms::create(['room_id' => $room->id, 'status' => 'booked']);
        try {
            app(CheckoutAction::class)->execute(new BoardingHouse, $room);
            $this->fail('Expected checkout rejection.');
        } catch (\Exception $e) {
            $this->assertSame('Kamar tidak dalam status terisi (occupied)', $e->getMessage());
        }
        $this->assertSame('booked', $room->fresh()->status);
        $this->assertSame('booked', $tenancy->fresh()->status);
        $this->assertDatabaseCount('log_activities', 0);
    }
}
