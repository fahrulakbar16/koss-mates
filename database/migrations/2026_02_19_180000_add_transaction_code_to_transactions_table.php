<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('transaction_code')->after('id')->nullable();
        });

        // Generate codes for existing transactions
        $transactions = DB::table('transactions')->get();
        foreach ($transactions as $transaction) {
            DB::table('transactions')->where('id', $transaction->id)->update([
                'transaction_code' => 'TRX-' . date('Ymd', strtotime($transaction->created_at)) . '-' . strtoupper(Str::random(5))
            ]);
        }

        // Make it unique and not null after populating
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('transaction_code')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('transaction_code');
        });
    }
};
