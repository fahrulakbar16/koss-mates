<?php

namespace Tests\Feature;

use App\Models\TransactionLog;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SuccessfulTransactionLogTest extends TestCase
{
    public function test_pending_payment_is_hidden_even_when_its_log_is_completed(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('transaction_id');
            $table->string('payment_status');
        });
        Schema::create('transaction_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('transaction_id')->nullable();
            $table->string('type');
            $table->string('status');
            $table->string('reference_number')->nullable();
            $table->integer('amount');
        });
        DB::table('payments')->insert([
            ['id' => 15, 'transaction_id' => 144, 'payment_status' => 'success'],
            ['id' => 150, 'transaction_id' => 144, 'payment_status' => 'pending'],
            ['id' => 132, 'transaction_id' => 130, 'payment_status' => 'success'],
            ['id' => 151, 'transaction_id' => 145, 'payment_status' => 'failed'],
        ]);
        foreach ([
            [1, 144, 'payment', 'completed', 'PAY-150-1790395628', 1500000],
            [2, 130, 'payment', 'completed', 'PAY-132-1790395440', 1500000],
            [3, 145, 'payment', 'completed', 'PAY-151-123', 1500000],
            [4, null, 'income', 'completed', null, 500],
            [5, null, 'expense', 'completed', null, 50],
            [6, null, 'income', 'pending', null, 700],
            [7, 130, 'payment', 'completed', null, 0],
            [8, 144, 'payment', 'completed', 'PAY-999-123', 900],
        ] as [$id, $transactionId, $type, $status, $reference, $amount]) {
            DB::table('transaction_logs')->insert([
                'id' => $id, 'transaction_id' => $transactionId, 'type' => $type,
                'status' => $status, 'reference_number' => $reference, 'amount' => $amount,
            ]);
        }

        $this->assertSame([2, 4, 5, 7], TransactionLog::successful()->orderBy('id')->pluck('id')->all());
        $this->assertEquals(1500500, TransactionLog::successful()->whereIn('type', ['payment', 'income'])->sum('amount'));
        DB::table('payments')->where('id', 132)->update(['payment_status' => 'pending']);
        $this->assertSame([4, 5], TransactionLog::successful()->orderBy('id')->pluck('id')->all());
    }
}
