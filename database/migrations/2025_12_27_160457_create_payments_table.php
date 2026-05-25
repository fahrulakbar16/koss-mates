<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->enum('payment_sequence', ['installment', 'full'])->default('full');
            $table->integer('amount');
            $table->enum('payment_method', ['cash', 'gateway']);
            $table->enum('payment_status', ['pending', 'success', 'failed'])->default('pending');
            $table->dateTime('payment_date')->nullable();
            $table->string('proof')->nullable(); // For offline/manual check
            $table->string('snap_token')->nullable();
            $table->json('gateway_response')->nullable(); // For online debug
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
