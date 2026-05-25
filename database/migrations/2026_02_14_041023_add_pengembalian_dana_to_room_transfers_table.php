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
            $table->decimal('pengembalian_dana', 15, 2)->default(0)->after('kekurangan_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_transfers', function (Blueprint $table) {
            $table->dropColumn('pengembalian_dana');
        });
    }
};
