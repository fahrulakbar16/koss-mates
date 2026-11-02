<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const PARENTS = [
        'user_id' => 'users',
        'room_id' => 'rooms',
        'room_price_id' => 'rooms_price',
        'user_room_id' => 'user_rooms',
    ];

    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->softDeletes();
            // Database cascades bypass Eloquent's SoftDeletes trait.
            foreach (self::PARENTS as $column => $parent) {
                $table->dropForeign([$column]);
                $table->foreign($column)->references('id')->on($parent)->restrictOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            foreach (self::PARENTS as $column => $parent) {
                $table->dropForeign([$column]);
                $table->foreign($column)->references('id')->on($parent)->cascadeOnDelete();
            }
            $table->dropSoftDeletes();
        });
    }
};
