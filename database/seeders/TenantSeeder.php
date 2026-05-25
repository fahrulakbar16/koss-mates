<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'username' => 'penyewa_dummy_' . $i,
                'password' => Hash::make('123123'),
            ]);
            
            $user->syncRoles('Penyewa');

            Tenant::create([
                'user_id' => $user->id,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'id_card_number' => $faker->numerify('################'),
                'birth_date' => $faker->date('Y-m-d', '-18 years'),
                'gender' => $faker->randomElement(['male', 'female']),
                'emergency_contact' => $faker->phoneNumber,
                'is_moved' => false,
                'phone_verified_at' => now(),
            ]);
        }
    }
}
