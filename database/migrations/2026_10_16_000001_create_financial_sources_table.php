<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_sources', function (Blueprint $table) {
            $table->id();
            // Refers to the financial ledger, including manual income and expenses.
            $table->foreignId('transaction_log_id')->unique()->constrained('transaction_logs')->cascadeOnDelete();
            $table->text('keterangan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_sources');
    }
};
