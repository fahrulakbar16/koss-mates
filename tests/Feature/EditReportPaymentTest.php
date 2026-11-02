<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\TransactionController;
use App\Models\TransactionLog;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EditReportPaymentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('boarding_houses', fn (Blueprint $t) => $t->id());
        Schema::create('rooms', function (Blueprint $t) {
            $t->id(); $t->integer('boarding_house_id');
        });
        Schema::create('rooms_price', fn (Blueprint $t) => $t->id());
        Schema::create('transactions', function (Blueprint $t) {
            $t->id(); $t->integer('room_id'); $t->integer('total_price');
            $t->integer('room_price_id')->nullable(); $t->string('status'); $t->timestamps(); $t->softDeletes();
        });
        Schema::create('payments', function (Blueprint $t) {
            $t->id(); $t->integer('transaction_id'); $t->integer('amount');
            $t->string('payment_status'); $t->string('payment_method'); $t->dateTime('payment_date'); $t->timestamps();
        });
        Schema::create('transaction_logs', function (Blueprint $t) {
            $t->id(); $t->integer('transaction_id'); $t->integer('boarding_house_id');
            $t->string('type'); $t->integer('amount'); $t->string('status');
            $t->string('payment_method'); $t->string('reference_number')->nullable(); $t->dateTime('transaction_date'); $t->timestamps();
        });
        DB::table('boarding_houses')->insert(['id' => 1]);
        DB::table('rooms')->insert(['id' => 1, 'boarding_house_id' => 1]);
        DB::table('transactions')->insert(['id' => 1, 'room_id' => 1, 'total_price' => 1000, 'status' => 'completed']);
        foreach ([1 => 600, 10 => 400] as $id => $amount) {
            DB::table('payments')->insert(['id' => $id, 'transaction_id' => 1, 'amount' => $amount,
                'payment_status' => 'success', 'payment_method' => 'cash', 'payment_date' => '2026-10-01 00:00:00']);
            DB::table('transaction_logs')->insert(['id' => $id, 'transaction_id' => 1, 'boarding_house_id' => 1,
                'type' => 'payment', 'amount' => $amount, 'status' => 'completed', 'payment_method' => 'cash',
                'reference_number' => "PAY-{$id}-12345", 'transaction_date' => '2026-10-01 00:00:00']);
        }
    }

    public function test_edit_updates_payment_log_and_bill_without_touching_another_installment(): void
    {
        Gate::shouldReceive('allows')->with('rooms.edit')->andReturn(true);
        $user = \Mockery::mock(User::class)->makePartial();
        $user->shouldReceive('hasRole')->with('Superadmin')->andReturn(true);
        $request = Request::create('/', 'PUT', ['amount' => 500, 'payment_date' => '2026-10-15', 'payment_method' => 'gateway']);
        $request->setUserResolver(fn () => $user);
        $response = app(TransactionController::class)->updatePayment($request, TransactionLog::findOrFail(1));
        $this->assertTrue($response->isRedirect());
        $this->assertDatabaseHas('payments', ['id' => 1, 'amount' => 500, 'payment_method' => 'gateway', 'payment_date' => '2026-10-15 00:00:00']);
        $this->assertDatabaseHas('transaction_logs', ['id' => 1, 'amount' => 500, 'payment_method' => 'gateway', 'transaction_date' => '2026-10-15 00:00:00']);
        $this->assertDatabaseHas('transactions', ['id' => 1, 'status' => 'incomplete']);
        $this->assertDatabaseHas('transaction_logs', ['id' => 10, 'amount' => 400, 'transaction_date' => '2026-10-01 00:00:00']);
        $this->assertDatabaseCount('transaction_logs', 2);
    }

    public function test_room_payment_date_edit_updates_legacy_log_with_a_single_payment(): void
    {
        DB::table('payments')->where('id', 10)->delete();
        DB::table('transaction_logs')->where('id', 10)->delete();
        // Imported logs were created without a payment reference.
        DB::table('transaction_logs')->where('id', 1)->update(['reference_number' => null]);
        $request = Request::create('/', 'PUT', [
            'amount' => 600, 'payment_date' => '2026-11-01',
            'payment_method' => 'cash', 'payment_status' => 'success',
        ]);
        app(\App\Http\Controllers\RoomController::class)->updatePayment(
            $request, \App\Models\BoardingHouse::findOrFail(1),
            \App\Models\Room::findOrFail(1), \App\Models\payment::findOrFail(1)
        );
        $this->assertDatabaseHas('payments', ['id' => 1, 'payment_date' => '2026-11-01 00:00:00']);
        $this->assertDatabaseHas('transaction_logs', ['id' => 1, 'transaction_date' => '2026-11-01 00:00:00']);
        $this->assertDatabaseCount('transaction_logs', 1);
    }

    public function test_observer_does_not_guess_which_installment_owns_a_legacy_log(): void
    {
        DB::table('transaction_logs')->where('id', 10)->update(['reference_number' => '']);
        \App\Models\payment::findOrFail(1)->update(['payment_date' => '2026-11-01']);
        $this->assertDatabaseHas('transaction_logs', ['id' => 1, 'transaction_date' => '2026-11-01 00:00:00']);
        $this->assertDatabaseHas('transaction_logs', ['id' => 10, 'transaction_date' => '2026-10-01 00:00:00']);
        $this->assertDatabaseCount('transaction_logs', 2);
    }

    public function test_expense_date_is_shared_by_resource_and_pdf(): void
    {
        $log = new TransactionLog([
            'type' => 'expense', 'amount' => 500, 'description' => 'Listrik',
            'transaction_date' => '2026-10-15 00:00:00',
        ]);
        $expense = (new \App\Http\Resources\Admin\FinancialReport\ExpenseResource($log))->toArray(Request::create('/'));
        $this->assertSame('2026-10-15', $expense['expense_date']);
        $html = view('admin.financial-reports.recap-pdf', [
            'boardingHouse' => (object) ['name' => 'Kos Test', 'address' => 'Alamat', 'persentasi_pemilik' => 90],
            'periodLabel' => 'Oktober 2026', 'tenants' => [], 'rooms' => [], 'expenses' => [$expense],
            'summary' => ['total_income' => 1000, 'total_expense' => 500, 'net_profit' => 500, 'management_share' => 50, 'owner_share' => 450],
        ])->render();
        $this->assertStringContainsString('TANGGAL', $html);
        $this->assertStringContainsString('15/10/2026', $html);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('a4', 'landscape')->output();
        $this->assertStringStartsWith('%PDF-', $pdf);
    }

    public function test_reference_parser_does_not_guess_legacy_payment_ids(): void
    {
        $log = new TransactionLog(['type' => 'payment', 'reference_number' => 'PAY-10-123']);
        $this->assertSame(10, $log->referencedPaymentId());
        $log->reference_number = null;
        $this->assertNull($log->referencedPaymentId());
        $log->reference_number = 'EXP-10';
        $this->assertNull($log->referencedPaymentId());
    }
}
