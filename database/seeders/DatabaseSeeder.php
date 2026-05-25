<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Access control
            PermissionSeeder::class,
            RoleSeeder::class,

            // Users
            UserSeeder::class,
            TenantSeeder::class,

            // Clusters (must be before boarding houses)
            // ClusterSeeder::class,

            // Boarding houses (must be before rooms)
            // BoardingHouseSeeder::class,

            // Rooms (must be before room prices)
            // RoomSeeder::class,
            // RoomPriceSeeder::class,
            
            // Transactions & Expenses
            // BookingSeeder::class,
            // ExpenseSeeder::class,
        ]);
    }
}
