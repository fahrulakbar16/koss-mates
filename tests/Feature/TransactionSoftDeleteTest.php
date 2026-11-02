<?php

namespace Tests\Feature;

use App\Models\Transaction;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TransactionSoftDeleteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        foreach (['users', 'rooms', 'rooms_price', 'user_rooms'] as $parent) {
            Schema::create($parent, fn (Blueprint $t) => $t->id());
            DB::table($parent)->insert(['id' => 1]);
        }
        Schema::create('transactions', function (Blueprint $t) {
            $t->id();
            foreach (['user_id' => 'users', 'room_id' => 'rooms', 'room_price_id' => 'rooms_price', 'user_room_id' => 'user_rooms'] as $column => $parent) {
                $t->foreignId($column)->constrained($parent)->cascadeOnDelete();
            }
            $t->timestamps();
        });
        foreach (['payments', 'transaction_logs'] as $child) {
            Schema::create($child, function (Blueprint $t) {
                $t->id();
                $t->foreignId('transaction_id')->constrained()->cascadeOnDelete();
            });
        }
        (require database_path('migrations/2026_11_03_000001_add_soft_deletes_to_transactions_table.php'))->up();
        DB::table('transactions')->insert(['id' => 1, 'user_id' => 1, 'room_id' => 1, 'room_price_id' => 1, 'user_room_id' => 1]);
        foreach (['payments', 'transaction_logs'] as $child) {
            DB::table($child)->insert(['id' => 1, 'transaction_id' => 1]);
        }
    }

    public function test_delete_preserves_record_and_children_and_can_be_restored(): void
    {
        Transaction::findOrFail(1)->delete();

        $this->assertSoftDeleted('transactions', ['id' => 1]);
        $this->assertNull(Transaction::find(1));
        $this->assertDatabaseHas('payments', ['transaction_id' => 1]);
        $this->assertDatabaseHas('transaction_logs', ['transaction_id' => 1]);

        Transaction::withTrashed()->findOrFail(1)->restore();
        $this->assertNotNull(Transaction::find(1));
    }

    public function test_parent_deletion_cannot_cascade_into_soft_deleted_transactions(): void
    {
        Transaction::findOrFail(1)->delete();
        foreach (['users', 'rooms', 'rooms_price', 'user_rooms'] as $parent) {
            try {
                DB::table($parent)->where('id', 1)->delete();
                $this->fail("Deletion of {$parent} must be restricted.");
            } catch (QueryException $exception) {
                $this->assertDatabaseHas($parent, ['id' => 1]);
                $this->assertSoftDeleted('transactions', ['id' => 1]);
            }
        }
    }
}
