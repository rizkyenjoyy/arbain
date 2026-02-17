<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Permissions yang dibutuhkan
        $permissions = [
            'access admin',
            'access agen',
        ];

        // Buat Permissions jika belum ada
        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
            }
        }

        // Definisikan roles dan permissions yang sesuai
        $rolesPermissions = [
            'Admin' => [
                'access admin',

            ],
            'Agen' => [
                'access agen',
            ],
        ];

        // Sinkronisasi roles dan permissions
        foreach ($rolesPermissions as $role => $permissions) {
            $roleModel = Role::firstOrCreate(['name' => $role]);

            // Jika permissions adalah array, sync dengan role
            if (is_array($permissions)) {
                $roleModel->syncPermissions($permissions);
            }
        }

        // Hapus role yang tidak digunakan lagi
        Role::whereNotIn('name', array_keys($rolesPermissions))->delete();
    }
}
