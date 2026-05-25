<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expense;
use App\Models\BoardingHouse;
use App\Models\User;
use Faker\Factory as Faker;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        $pemilik = User::whereHas('roles', function ($query) {
            $query->where('name', 'Pemilik');
        })->first();

        $boardingHouses = BoardingHouse::all();

        if (!$pemilik || $boardingHouses->isEmpty()) {
            return;
        }

        $categories = ['Listrik', 'Air', 'Internet', 'Kebersihan', 'Perbaikan', 'Lain-lain'];
        
        foreach ($boardingHouses as $boardingHouse) {
            for ($i = 0; $i < 3; $i++) {
                Expense::create([
                    'user_id' => $pemilik->id,
                    'boarding_house_id' => $boardingHouse->id,
                    'category' => $faker->randomElement($categories),
                    'description' => $faker->sentence(),
                    'amount' => $faker->numberBetween(50000, 500000),
                    'expense_date' => $faker->dateTimeBetween('-1 month', 'now'),
                    'status' => 'selesai',
                ]);
            }
        }
    }
}
