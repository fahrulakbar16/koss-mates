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
        if (DB::getDriverName() !== 'sqlite') {
            // For MySQL, we need to use DB::statement because enum change is not perfectly supported via Blueprint on all versions
            // Redefining the enum to include 'income'
            DB::statement("ALTER TABLE transaction_logs MODIFY COLUMN type ENUM('payment', 'deposit', 'refund', 'expense', 'other', 'income') DEFAULT 'payment'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            // Remove 'income' from the enum. 
            // Note: Any existing 'income' records will become invalid or default to the first enum value if we are not careful.
            // Usually, we filter those first.
            DB::statement("ALTER TABLE transaction_logs MODIFY COLUMN type ENUM('payment', 'deposit', 'refund', 'expense', 'other') DEFAULT 'payment'");
        }
    }
};
