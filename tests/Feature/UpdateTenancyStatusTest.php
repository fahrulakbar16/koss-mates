<?php

namespace Tests\Feature;

use App\Actions\Room\UpdateTenancyStatusAction;
use App\Http\Controllers\RoomController;
use App\Models\BoardingHouse;
use App\Models\Room;
use App\Models\UserRooms;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class UpdateTenancyStatusTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('users', fn (Blueprint $t) => $t->id());
        Schema::create('rooms', function (Blueprint $t) {
            $t->id(); $t->integer('boarding_house_id'); $t->string('name');
            $t->integer('capacity')->default(1); $t->string('status'); $t->timestamps();
        });
        Schema::create('user_rooms', function (Blueprint $t) {
            $t->id(); $t->integer('boarding_house_id'); $t->integer('room_id');
            $t->string('status'); $t->date('start_date')->nullable(); $t->date('end_date')->nullable(); $t->timestamps();
        });
        (require database_path('migrations/2026_02_08_213404_create_log_activities_table.php'))->up();
    }

    private function room(): Room
    {
        return Room::create(['name' => '703', 'boarding_house_id' => 1, 'status' => 'occupied']);
    }

    private function tenancy(Room $room, string $status): UserRooms
    {
        return UserRooms::create(['boarding_house_id' => 1, 'room_id' => $room->id,
            'status' => $status, 'start_date' => '2026-07-31', 'end_date' => '2026-11-30']);
    }

    public function test_status_correction_keeps_dates_and_derives_room_status_from_all_tenancies(): void
    {
        $room = $this->room();
        $old = $this->tenancy($room, 'booked');
        $current = $this->tenancy($room, 'checkin_open');
        $action = app(UpdateTenancyStatusAction::class);
        $action->execute($room, $old->id, 'cancelled', 'booked');
        $this->assertSame('occupied', $room->fresh()->status);
        $this->assertSame('checkin_open', $current->fresh()->status);
        $action->execute($room, $current->id, 'booked', 'checkin_open');
        $this->assertSame('booked', $room->fresh()->status);
        $action->execute($room, $current->id, 'checked_out', 'booked');
        $this->assertSame('available', $room->fresh()->status);
        $this->assertSame('2026-07-31', $current->fresh()->start_date);
        $this->assertSame('2026-11-30', $current->fresh()->end_date);
        $this->assertDatabaseCount('user_rooms', 2);
        $this->assertDatabaseCount('log_activities', 3);
    }

    public function test_booked_tenant_can_be_checked_in(): void
    {
        $room = $this->room();
        $room->update(['status' => 'booked']);
        $tenancy = $this->tenancy($room, 'booked');
        app(UpdateTenancyStatusAction::class)->execute($room, $tenancy->id, 'checked_in', 'booked');
        $this->assertSame('checked_in', $tenancy->fresh()->status);
        $this->assertSame('occupied', $room->fresh()->status);
    }

    public function test_cannot_update_a_record_from_another_room(): void
    {
        $room = $this->room();
        $tenancy = $this->tenancy($this->room(), 'booked');
        $this->expectException(ModelNotFoundException::class);
        app(UpdateTenancyStatusAction::class)->execute($room, $tenancy->id, 'cancelled', 'booked');
    }

    public function test_stale_status_is_rejected_without_changes(): void
    {
        $room = $this->room();
        $tenancy = $this->tenancy($room, 'checked_in');
        try {
            app(UpdateTenancyStatusAction::class)->execute($room, $tenancy->id, 'cancelled', 'booked');
            $this->fail('Stale update must be rejected.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('status', $e->errors());
        }
        $this->assertSame('checked_in', $tenancy->fresh()->status);
        $this->assertDatabaseCount('log_activities', 0);
    }

    public function test_closed_tenancies_cannot_be_changed_even_when_room_is_available(): void
    {
        $room = $this->room();
        $room->update(['status' => 'available']);
        foreach (['cancelled', 'checked_out'] as $status) {
            $tenancy = $this->tenancy($room, $status);
            foreach (['booked', 'checked_in', 'cancelled'] as $targetStatus) {
                try {
                    app(UpdateTenancyStatusAction::class)->execute($room, $tenancy->id, $targetStatus, $status);
                    $this->fail('Closed tenancy must not be editable.');
                } catch (ValidationException $e) {
                    $this->assertArrayHasKey('status', $e->errors());
                }
                $this->assertSame($status, $tenancy->fresh()->status);
                $this->assertSame('available', $room->fresh()->status);
            }
        }
        $this->assertDatabaseCount('log_activities', 0);
    }

    public function test_requires_room_edit_permission(): void
    {
        Gate::shouldReceive('allows')->once()->with('rooms.edit')->andReturn(false);
        try {
            app(RoomController::class)->updateTenancyStatus(Request::create('/'), new BoardingHouse, new Room);
            $this->fail('Unauthorized update must fail.');
        } catch (HttpException $e) {
            $this->assertSame(403, $e->getStatusCode());
        }
    }
}
