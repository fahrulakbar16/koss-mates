<?php

namespace Tests\Feature;

use App\Actions\Room\StoreRoomPricesAction;
use App\Models\Room;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class StoreRoomPricesTest extends TestCase
{
    public function test_updating_prices_preserves_tenancies_transactions_and_room_status(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('rooms', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('status'); $t->timestamps();
        });
        Schema::create('rooms_price', function (Blueprint $t) {
            $t->id(); $t->foreignId('room_id')->constrained('rooms');
            $t->integer('duration'); $t->decimal('price');
            $t->string('name')->nullable(); $t->text('addons')->nullable(); $t->timestamps();
        });
        foreach (['user_rooms', 'transactions'] as $table) {
            Schema::create($table, function (Blueprint $t) {
                $t->id();
                $t->foreignId('room_price_id')->constrained('rooms_price')->cascadeOnDelete();
            });
        }
        Schema::create('log_activities', function (Blueprint $t) {
            $t->id(); $t->string('subject'); $t->text('url'); $t->string('method');
            $t->string('ip')->nullable(); $t->text('agent')->nullable();
            $t->unsignedBigInteger('user_id')->nullable(); $t->text('properties')->nullable(); $t->timestamps();
        });

        $room = Room::create(['name' => '703', 'status' => 'occupied']);
        $price = $room->prices()->create(['duration' => 1, 'price' => 1000000]);
        $other = $room->prices()->create(['duration' => 3, 'price' => 2800000]);
        DB::table('user_rooms')->insert(['id' => 1, 'room_price_id' => $price->id]);
        DB::table('transactions')->insert(['id' => 1, 'room_price_id' => $price->id]);

        $action = app(StoreRoomPricesAction::class);
        $action->execute($room, [
            ['id' => $price->id, 'duration' => 2, 'price' => 1900000],
            ['duration' => 6, 'price' => 5400000],
        ]);
        // Older callers without an ID must also update the existing duration.
        $action->execute($room, [['duration' => 2, 'price' => 1800000]]);

        $this->assertDatabaseHas('rooms_price', ['id' => $price->id, 'duration' => 2, 'price' => 1800000]);
        $this->assertDatabaseHas('rooms_price', ['id' => $other->id, 'price' => 2800000]);
        $this->assertDatabaseCount('rooms_price', 3);
        $this->assertDatabaseHas('user_rooms', ['id' => 1, 'room_price_id' => $price->id]);
        $this->assertDatabaseHas('transactions', ['id' => 1, 'room_price_id' => $price->id]);
        $this->assertSame('occupied', $room->fresh()->status);
    }
}
