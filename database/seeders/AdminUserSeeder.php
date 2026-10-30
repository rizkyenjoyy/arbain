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
        // Pastikan role admin dan agen ada
        $roleAdmin = Role::firstOrCreate(['name' => 'Admin']);
        $roleAgen = Role::firstOrCreate(['name' => 'Agen']);

        // Buat user admin
        $admin = User::firstOrCreate([
            'username' => 'adminuser',
        ], [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone' => '081234567890',
            'password' => Hash::make('123456789'),
            'is_active' => true,
        ]);
        $admin->assignRole('Admin');

        // Buat user agen
        $agen = User::firstOrCreate([
            'username' => 'agenuser',
        ], [
            'name' => 'Agen User',
            'email' => 'agen@example.com',
            'phone' => '081234567891',
            'password' => Hash::make('123456789'),
            'is_active' => true,
        ]);
        $agen->assignRole('Agen');
    }
}

