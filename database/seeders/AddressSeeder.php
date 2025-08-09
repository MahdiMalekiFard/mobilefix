<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Address::create([
            'user_id' => 1,
            'title' => 'Home',
            'address' => '123 Main St, New York, USA',
            'is_default' => true,
        ]);

        Address::create([
            'user_id' => 1,
            'title' => 'Work',
            'address' => '456 Main St, Washington, USA',
            'is_default' => false,
        ]);
    }
}
