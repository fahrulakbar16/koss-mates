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
        Schema::table('rooms_price', function (Blueprint $table) {
            if (!Schema::hasColumn('rooms_price', 'name')) {
                $table->string('name')->nullable()->after('price');
            }
            if (!Schema::hasColumn('rooms_price', 'monthly_expense')) {
                $table->decimal('monthly_expense', 15, 2)->default(0)->after('name');
            }
            if (!Schema::hasColumn('rooms_price', 'addons')) {
                $table->json('addons')->nullable()->after('monthly_expense');
            }
        });

        // Migrate data if table exists
        if (Schema::hasTable('room_price_details')) {
            $details = DB::table('room_price_details')->get();
            foreach ($details as $detail) {
                DB::table('rooms_price')
                    ->where('id', $detail->room_price_id)
                    ->update([
                        'name' => $detail->name,
                        'monthly_expense' => $detail->monthly_expense,
                        'addons' => $detail->addons ?? null,
                    ]);
            }
            Schema::dropIfExists('room_price_details');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('room_price_details')) {
            Schema::create('room_price_details', function (Blueprint $table) {
                $table->id();
                $table->foreignId('room_price_id')->constrained('rooms_price')->onDelete('cascade');
                $table->string('name')->nullable();
                $table->decimal('monthly_expense', 15, 2)->default(0);
                $table->json('addons')->nullable();
                $table->timestamps();
            });

            $prices = DB::table('rooms_price')->get();
            foreach ($prices as $price) {
                DB::table('room_price_details')->insert([
                    'room_price_id' => $price->id,
                    'name' => $price->name,
                    'monthly_expense' => $price->monthly_expense,
                    'addons' => $price->addons,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        Schema::table('rooms_price', function (Blueprint $table) {
            $table->dropColumn(['name', 'monthly_expense', 'addons']);
        });
    }
};
