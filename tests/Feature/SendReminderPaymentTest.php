<?php

namespace Tests\Feature;

use App\Services\FonnteApiService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SendReminderPaymentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Isolated in-memory schema: never use the application's database.
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('room_id')->nullable();
            $table->integer('total_price');
            $table->string('status');
            $table->string('transaction_code');
            $table->date('jatuh_tempo')->nullable();
            $table->timestamps();
        });
        (require database_path('migrations/2026_09_11_000000_add_last_reminder_sent_at_to_transactions_table.php'))->up();
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('phone');
        });
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('transaction_id');
            $table->integer('amount');
            $table->string('payment_status');
        });
        $this->travelTo(now()->setDate(2026, 9, 11)->startOfDay());
        DB::table('users')->insert(['id' => 1, 'name' => 'Budi']);
        DB::table('tenants')->insert(['user_id' => 1, 'phone' => '+62 812-3456-7890']);
        Http::preventStrayRequests();
    }

    private function transaction(array $attributes = []): int
    {
        return DB::table('transactions')->insertGetId(array_merge([
            'user_id' => 1, 'total_price' => 1000000, 'status' => 'incomplete',
            'transaction_code' => 'TRX-test', 'jatuh_tempo' => '2026-09-16',
        ], $attributes));
    }

    public function test_partial_payment_and_daily_deduplication(): void
    {
        $id = $this->transaction();
        DB::table('payments')->insert([
            ['transaction_id' => $id, 'amount' => 400000, 'payment_status' => 'success'],
            ['transaction_id' => $id, 'amount' => 100000, 'payment_status' => 'pending'],
        ]);
        Http::fake(['*' => Http::response(['status' => true])]);
        $this->artisan('billing:send-reminders')->assertSuccessful();
        $this->artisan('billing:send-reminders')->assertSuccessful();
        Http::assertSentCount(1);
        Http::assertSent(fn ($request) => $request['target'] === '6281234567890'
            && str_contains($request['message'], 'Rp 600.000')
            && str_contains($request['message'], 'dalam 5 hari'));
        $this->travel(1)->days();
        $this->artisan('billing:send-reminders')->assertSuccessful();
        Http::assertSentCount(2);
    }

    public function test_api_rejection_can_be_retried_and_does_not_stop_other_transactions(): void
    {
        $first = $this->transaction();
        $second = $this->transaction();
        Http::fake(['*' => Http::sequence()->push(['status' => false, 'reason' => 'Rejected'])
            ->push(['status' => true])->push(['status' => true])]);
        $this->artisan('billing:send-reminders')->assertFailed();
        $this->assertNull(DB::table('transactions')->find($first)->last_reminder_sent_at);
        $this->assertNotNull(DB::table('transactions')->find($second)->last_reminder_sent_at);
        $this->artisan('billing:send-reminders')->assertSuccessful();
        Http::assertSentCount(3);
    }

    public function test_ineligible_or_invalid_transactions_are_skipped(): void
    {
        $this->transaction(['status' => 'completed']);
        $this->transaction(['status' => 'cancelled']);
        $this->transaction(['jatuh_tempo' => null]);
        $this->transaction(['jatuh_tempo' => '2026-09-17']);
        $this->transaction(['user_id' => null]);
        $id = $this->transaction();
        DB::table('payments')->insert(['transaction_id' => $id, 'amount' => 1000000, 'payment_status' => 'success']);
        DB::table('tenants')->update(['phone' => 'invalid']);
        $this->transaction();
        Http::fake();
        $this->artisan('billing:send-reminders')->assertSuccessful();
        Http::assertNothingSent();
    }

    public function test_today_and_old_overdue_transactions_are_reminded(): void
    {
        $this->transaction(['jatuh_tempo' => '2026-09-11']);
        $this->transaction(['jatuh_tempo' => '2026-08-01']);
        Http::fake(['*' => Http::response(['status' => true])]);
        $this->artisan('billing:send-reminders')->assertSuccessful();
        Http::assertSentCount(2);
        Http::assertSent(fn ($request) => str_contains($request['message'], '*HARI INI*'));
        Http::assertSent(fn ($request) => str_contains($request['message'], 'selama 41 hari'));
    }

    public function test_http_failure_is_reported_by_service(): void
    {
        Http::fake(['*' => Http::response(['reason' => 'Unavailable'], 503)]);
        $this->assertFalse(app(FonnteApiService::class)->sendMessage('6281234567890', 'Test')['success']);
    }
}
