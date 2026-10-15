<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaction_logs', function (Blueprint $table) {
            $table->foreignId('expense_id')->nullable()->constrained('expenses')->cascadeOnDelete();
        });

        // Only exact, existing references establish ownership. Ambiguous imports remain untouched.
        DB::table('transaction_logs')->where('type', 'expense')->orderBy('id')->chunkById(200, function ($logs) {
            foreach ($logs as $log) {
                if (!preg_match('/^EXP-([1-9][0-9]*)$/D', $log->reference_number ?? '', $match)) {
                    continue;
                }
                $expense = DB::table('expenses')->where('id', $match[1])->first();
                if ($expense) {
                    DB::table('transaction_logs')->where('id', $log->id)->update([
                        'expense_id' => $expense->id,
                        'amount' => abs($log->amount),
                    ]);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('transaction_logs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('expense_id');
        });
    }
};
