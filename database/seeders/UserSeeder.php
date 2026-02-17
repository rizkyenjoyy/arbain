<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 20; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'username' => $faker->unique()->userName,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->unique()->phoneNumber,
                'nik' => $faker->optional()->numerify('##########'),
                'password' => Hash::make('password'), // Password default untuk semua user
            ]);

            // Assign role secara acak atau sesuai kebutuhan
            $role = Role::inRandomOrder()->first();
            $user->assignRole($role);
        }
    }
}
