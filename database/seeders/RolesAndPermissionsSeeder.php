<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for regular users (web guard)
        $userPermissions = [
            'create posts',
            'edit posts',
            'delete posts',
            'view posts'
        ];

        foreach ($userPermissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create permissions for admins (admin guard)
        $adminPermissions = [
            'manage users',
            'manage posts',
            'manage settings',
            'view dashboard'
        ];

        foreach ($adminPermissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'admin']);
        }

        // Create user roles and assign permissions
        $userRole = Role::create(['name' => 'user', 'guard_name' => 'web']);
        $userRole->givePermissionTo(['view posts', 'create posts']);

        $editorRole = Role::create(['name' => 'editor', 'guard_name' => 'web']);
        $editorRole->givePermissionTo(['view posts', 'create posts', 'edit posts']);

        // Create admin roles and assign permissions
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'admin']);
        $adminRole->givePermissionTo(['view dashboard', 'manage posts']);

        $superAdminRole = Role::create(['name' => 'super-admin', 'guard_name' => 'admin']);
        $superAdminRole->givePermissionTo(Permission::where('guard_name', 'admin')->get());
    }
}
