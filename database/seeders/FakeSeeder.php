<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FakeSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            BlogSeeder::class,
            BrandSeeder::class,
            PaymentMethodSeeder::class,
            ProblemSeeder::class,
            AddressSeeder::class,
            SliderSeeder::class,
            OpinionSeeder::class,
            PageSeeder::class,
            FaqSeeder::class,
            ServiceSeeder::class,
        ]);
    }
}
