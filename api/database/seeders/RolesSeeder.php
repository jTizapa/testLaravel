<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permisos mínimos del dominio
        $permissions = [
            'members-view', 'members-create', 'members-update', 'members-delete',
            'plans-view', 'plans-create', 'plans-update', 'plans-delete',
            'payments-view', 'payments-create',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'web',
            ]);
        }

        $roles = [
            'admin',
            'manager',
            'staff',
            'member',
        ];

        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(
                [
                    'name' => $roleName,
                    'guard_name' => 'web',
                ]
            );

            // Asignación de permisos básica
            if ($roleName === 'admin') {
                $role->syncPermissions(Permission::all());
            } elseif ($roleName === 'manager') {
                $role->syncPermissions([
                    'members-view','members-create','members-update','members-delete',
                    'plans-view','plans-create','plans-update','plans-delete',
                    'payments-view','payments-create',
                ]);
            } elseif ($roleName === 'staff') {
                $role->syncPermissions([
                    'members-view','members-update',
                    'plans-view',
                    'payments-view',
                ]);
            } else { // member
                $role->syncPermissions([]);
            }
        }
    }
}
