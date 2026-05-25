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
        Schema::table('room_transfers', function (Blueprint $table) {
            $table->unsignedBigInteger('room_price_id')->nullable()->after('room_id');
            $table->decimal('sisa_pembayaran', 15, 2)->default(0)->after('room_price_id');
            $table->decimal('kekurangan_pembayaran', 15, 2)->default(0)->after('sisa_pembayaran');

            $table->foreign('room_price_id')->references('id')->on('rooms_price')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_transfers', function (Blueprint $table) {
            $table->dropForeign(['room_price_id']);
            $table->dropColumn(['room_price_id', 'sisa_pembayaran', 'kekurangan_pembayaran']);
        });
    }
};
