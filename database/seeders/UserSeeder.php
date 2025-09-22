<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $admin = User::updateOrCreate([
            'email' => 'test@gmail.com',
        ], [
            'name'     => 'Test User',
            'mobile'   => '09151234567',
            'password' => Hash::make('test1234'),
            'password_set_at' => now(),
        ]);
    }
}
