<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CreateMonthlyBillingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        foreach (['users', 'rooms', 'rooms_price', 'user_rooms', 'transactions', 'payments'] as $name) {
            Schema::create($name, function (Blueprint $table) use ($name) {
                $table->id();
                $table->timestamps();
                if (in_array($name, ['users', 'rooms'])) {
                    $table->string('name');
                }
                if ($name === 'rooms_price') {
                    $table->integer('room_id');
                    $table->integer('duration');
                    $table->integer('price');
                }
                if (in_array($name, ['user_rooms', 'transactions'])) {
                    $table->integer('user_id');
                    $table->integer('room_id');
                    $table->integer('room_price_id');
                    $table->string('status');
                }
                if ($name === 'user_rooms') {
                    $table->date('start_date')->nullable();
                    $table->boolean('verifikasi_admin');
                }
                if ($name === 'transactions') {
                    $table->integer('user_room_id');
                    $table->integer('total_price');
                    $table->string('payment_scheme');
                    $table->string('type');
                    $table->string('transaction_code');
                    $table->date('jatuh_tempo');
                }
                if ($name === 'payments') {
                    $table->integer('transaction_id');
                    $table->string('payment_sequence');
                    $table->integer('amount');
                    $table->string('payment_method');
                    $table->string('payment_status');
                }
            });
        }
        DB::table('users')->insert(['id' => 1, 'name' => 'Budi']);
        DB::table('rooms')->insert(['id' => 1, 'name' => 'A1']);
        DB::table('rooms_price')->insert(['id' => 1, 'room_id' => 1, 'duration' => 3, 'price' => 1000000]);
        DB::table('user_rooms')->insert(['user_id' => 1, 'room_id' => 1, 'room_price_id' => 1,
            'status' => 'checked_in', 'verifikasi_admin' => true, 'start_date' => '2026-01-31']);
    }

    public function test_plan_duration_lead_time_installments_and_duplicate_prevention(): void
    {
        DB::table('user_rooms')->update(['start_date' => '2026-01-15']);
        $this->travelTo(now()->setDate(2026, 3, 14)->startOfDay());
        $this->artisan('billing:create-monthly')->assertSuccessful();
        $this->assertSame(0, DB::table('transactions')->count());
        $this->travel(1)->days();
        $this->artisan('billing:create-monthly --payment-scheme=installment')->assertSuccessful();
        $this->artisan('billing:create-monthly --payment-scheme=installment')->assertSuccessful();
        $this->assertSame(1, DB::table('transactions')->count());
        $this->assertSame('2026-04-15', substr(DB::table('transactions')->value('jatuh_tempo'), 0, 10));
        $this->assertSame(3, DB::table('payments')->count());
        $this->assertEquals(1000000, DB::table('payments')->sum('amount'));
        $this->assertStringStartsWith('2026-03-15', DB::table('transactions')->value('created_at'));
    }

    public function test_month_end_anchor_recovers_and_dry_run_does_not_write(): void
    {
        DB::table('rooms_price')->update(['duration' => 1]);
        $this->travelTo(now()->setDate(2026, 2, 28)->startOfDay());
        $this->artisan('billing:create-monthly --dry-run')->assertSuccessful();
        $this->assertSame(0, DB::table('transactions')->count());
        $this->assertSame(0, DB::table('payments')->count());
        $this->artisan('billing:create-monthly')->assertSuccessful();
        $this->travelTo(now()->setDate(2026, 3, 1)->startOfDay());
        $this->artisan('billing:create-monthly')->assertSuccessful();
        $this->assertEquals(['2026-02-28', '2026-03-31'], DB::table('transactions')->orderBy('id')->pluck('jatuh_tempo')->map(fn ($date) => substr($date, 0, 10))->all());
    }

    public function test_end_of_month_bill_opens_on_clamped_previous_month_date(): void
    {
        DB::table('rooms_price')->update(['duration' => 1]);
        DB::table('user_rooms')->update(['start_date' => '2026-03-31']);
        $this->travelTo(now()->setDate(2026, 3, 29)->startOfDay());
        $this->artisan('billing:create-monthly')->assertSuccessful();
        $this->assertSame(0, DB::table('transactions')->count());
        $this->travel(1)->days();
        $this->artisan('billing:create-monthly')->assertSuccessful();
        $this->assertSame('2026-04-30', substr(DB::table('transactions')->value('jatuh_tempo'), 0, 10));
    }

    public function test_payment_failure_rolls_back_the_bill(): void
    {
        $this->travelTo(now()->setDate(2026, 4, 30)->startOfDay());
        Schema::drop('payments');
        $this->artisan('billing:create-monthly')->assertFailed();
        $this->assertSame(0, DB::table('transactions')->count());
    }

    public function test_current_overdue_cycle_is_created_without_historical_backfill(): void
    {
        $this->travelTo(now()->setDate(2026, 8, 5)->startOfDay());
        $this->artisan('billing:create-monthly')->assertSuccessful();
        $this->assertSame(1, DB::table('transactions')->count());
        $this->assertSame('2026-07-31', substr(DB::table('transactions')->value('jatuh_tempo'), 0, 10));
    }
}
