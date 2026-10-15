<?php

namespace Tests\Feature;

use App\Models\Expense;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ExpenseLogLifecycleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('expenses', function (Blueprint $t) {
            $t->id(); $t->integer('room_id')->nullable(); $t->integer('boarding_house_id')->nullable();
            $t->string('category')->nullable(); $t->string('description')->nullable();
            $t->decimal('amount'); $t->date('expense_date'); $t->timestamps();
        });
        Schema::create('transaction_logs', function (Blueprint $t) {
            $t->id(); $t->integer('room_id')->nullable(); $t->integer('boarding_house_id')->nullable();
            $t->string('type'); $t->string('status')->nullable(); $t->string('description')->nullable();
            $t->string('payment_method')->nullable(); $t->string('reference_number')->nullable();
            $t->decimal('amount'); $t->dateTime('transaction_date'); $t->timestamps();
        });
    }

    private function migrateLogs(): void
    {
        (require database_path('migrations/2026_10_15_000001_link_transaction_logs_to_expenses.php'))->up();
    }

    public function test_backfill_and_cascade_delete_all_linked_logs(): void
    {
        DB::table('expenses')->insert(['id' => 1, 'amount' => 100, 'expense_date' => '2026-10-15']);
        foreach (['EXP-1', 'EXP-1', null] as $ref) {
            DB::table('transaction_logs')->insert(['type' => 'expense', 'reference_number' => $ref, 'amount' => -100, 'transaction_date' => '2026-10-15']);
        }
        $this->migrateLogs();
        $this->assertEquals(2, DB::table('transaction_logs')->where('expense_id', 1)->where('amount', 100)->count());
        DB::table('expenses')->where('id', 1)->delete();
        $this->assertEquals(1, DB::table('transaction_logs')->count());
    }

    public function test_save_updates_one_log_with_positive_amount_and_current_room(): void
    {
        $this->migrateLogs();
        $expense = Expense::create(['amount' => 100, 'expense_date' => '2026-10-15', 'room_id' => 1]);
        $expense->update(['amount' => 200, 'room_id' => 2]);
        $this->assertEquals(1, $expense->transactionLogs()->count());
        $this->assertEquals(200, $expense->transactionLogs()->first()->amount);
        $this->assertEquals(2, $expense->transactionLogs()->first()->room_id);
        $expense->delete();
        $this->assertEquals(0, DB::table('transaction_logs')->count());
    }

    public function test_pruning_defaults_to_preview_and_preserves_ambiguous_and_non_expense_logs(): void
    {
        $this->migrateLogs();
        foreach ([['expense', 'EXP-999'], ['expense', null], ['income', null]] as [$type, $ref]) {
            DB::table('transaction_logs')->insert(['type' => $type, 'reference_number' => $ref, 'amount' => 100, 'transaction_date' => '2026-10-15']);
        }
        $this->artisan('transactions:prune-orphan-expense-logs')->assertSuccessful();
        $this->assertEquals(3, DB::table('transaction_logs')->count());
        $this->artisan('transactions:prune-orphan-expense-logs --delete')->assertSuccessful();
        $this->assertEquals(2, DB::table('transaction_logs')->count());
        $this->artisan('transactions:prune-orphan-expense-logs --delete')->assertSuccessful();
        $this->assertEquals(2, DB::table('transaction_logs')->count());
    }
}
