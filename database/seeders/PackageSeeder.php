<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;
use Faker\Factory as Faker;

class PackageSeeder extends Seeder
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
            Package::create([
                'name' => 'Paket ' . $faker->word,
                'description' => $faker->paragraph,
                'departure_date' => $faker->dateTimeBetween('+1 week', '+1 year')->format('Y-m-d'),
                'price' => $faker->randomFloat(2, 1000000, 20000000), // Harga antara 1 juta hingga 20 juta
            ]);
        }
    }
}
