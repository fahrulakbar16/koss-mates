<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Seed default WhatsApp device settings
        \DB::table('settings')->insertOrIgnore([
            ['key' => 'whatsapp_device_token', 'value' => '', 'group' => 'whatsapp', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'whatsapp_device_number', 'value' => '', 'group' => 'whatsapp', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::table('settings')->whereIn('key', ['whatsapp_device_token', 'whatsapp_device_number'])->delete();
    }
};
