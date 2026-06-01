<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clusters', function (Blueprint $table) {
            $table->foreignId('pengelola_id')->nullable()->after('id')->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('clusters', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\User::class, 'pengelola_id');
            $table->dropColumn('pengelola_id');
        });
    }
};
