<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            BlogSeeder::class,
            BrandSeeder::class,
            PaymentMethodSeeder::class,
        ]);
    }
}
