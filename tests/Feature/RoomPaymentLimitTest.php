<?php

namespace Tests\Feature;

use App\Http\Controllers\RoomController;
use App\Models\BoardingHouse;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\payment;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class RoomPaymentLimitTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('room_id');
            $table->integer('total_price');
        });
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('transaction_id');
            $table->integer('amount');
            $table->string('payment_status');
        });
        DB::table('transactions')->insert(['id' => 1, 'room_id' => 1, 'total_price' => 850000]);
        DB::table('payments')->insert([
            ['id' => 1, 'transaction_id' => 1, 'amount' => 300000, 'payment_status' => 'success'],
            ['id' => 2, 'transaction_id' => 1, 'amount' => 100000, 'payment_status' => 'success'],
        ]);
    }

    private function assertRejected(int $amount, ?int $paymentId, string $limit): void
    {
        $request = Request::create('/', 'POST', [
            'amount' => $amount, 'payment_status' => 'success',
            'payment_method' => 'cash', 'payment_date' => '2026-09-12',
        ]);
        $room = new Room;
        $room->id = 1;
        try {
            $controller = app(RoomController::class);
            if ($paymentId) {
                $controller->updatePayment($request, new BoardingHouse, $room, payment::findOrFail($paymentId));
            } else {
                $controller->storePayment($request, new BoardingHouse, $room, Transaction::findOrFail(1));
            }
            $this->fail('Expected amount validation to reject overpayment.');
        } catch (ValidationException $e) {
            $this->assertStringContainsString($limit, $e->errors()['amount'][0]);
        }
        $this->assertEquals(400000, DB::table('payments')->sum('amount'));
        $this->assertSame(0, DB::transactionLevel());
    }

    public function test_new_payment_cannot_exceed_remaining_balance(): void
    {
        $this->assertRejected(450001, null, '450.000');
    }

    public function test_edit_excludes_the_current_payment_from_paid_total(): void
    {
        $this->assertRejected(750001, 1, '750.000');
    }
}
