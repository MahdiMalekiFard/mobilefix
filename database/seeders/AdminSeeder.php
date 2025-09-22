<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $admin = User::updateOrCreate([
            'email' => 'info@Fix-mobil.de',
        ], [
            'name'     => 'Nasim shadab',
            'mobile'   => '4976489939',
            'password' => Hash::make('fixmobil9939'),
            'password_set_at' => now(),
        ]);
        
        $admin->assignRole(Role::where('name', RoleEnum::ADMIN->value)->first());
    }
}
