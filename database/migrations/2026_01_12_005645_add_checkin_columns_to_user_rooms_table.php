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
        Schema::table('user_rooms', function (Blueprint $table) {
            $table->string('foto_kamar')->nullable()->after('status');
            $table->boolean('verifikasi_admin')->default(false)->after('foto_kamar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_rooms', function (Blueprint $table) {
            $table->dropColumn(['foto_kamar', 'verifikasi_admin']);
        });
    }
};
