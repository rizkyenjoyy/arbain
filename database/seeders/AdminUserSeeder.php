<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Buat user admin
        $admin = User::firstOrCreate([
            'username' => 'adminuser', // username login
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone' => '123456789',
            'password' => Hash::make('123456789'), // Password di-hash
        ]);

        // Pastikan role admin ada
        $role = Role::firstOrCreate(['name' => 'Admin']);

        // Tambahkan role admin ke user
        $admin->assignRole('Admin');
    }
}

