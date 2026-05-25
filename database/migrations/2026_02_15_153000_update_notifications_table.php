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
        Schema::table('notifications', function (Blueprint $table) {
            // Check if column 'message' exists before renaming
            if (Schema::hasColumn('notifications', 'message')) {
                $table->renameColumn('message', 'body');
            } else if (!Schema::hasColumn('notifications', 'body')) {
                // Should not happen if migration history is consistent
                $table->text('body');
            }

            if (!Schema::hasColumn('notifications', 'data')) {
                $table->json('data')->nullable();
            }

            // Make user_id nullable for system-wide/broadcast notifications
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'body')) {
                $table->renameColumn('body', 'message');
            }
            if (Schema::hasColumn('notifications', 'data')) {
                $table->dropColumn('data');
            }
            // Revert user_id to not null (caution: might fail if null values exist)
            // $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
