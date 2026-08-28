<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define Granular System Permissions
        $permissions = [
            'manage-organization',
            'manage-users-roles',
            'manage-programs',
            'manage-projects',
            'manage-beneficiaries',
            'manage-finance-grants',
            'view-me-analytics',
            'conduct-field-monitoring',
            'manage-communications',
            'manage-zone-level',
            'manage-lga-level',
            'view-assigned-region-only',
        ];

        foreach ($permissions as $perm) {
            Permission::findOrCreate($perm, 'web');
        }

        // 2. Define Roles and Assign Corresponding Permission Matrix
        $roleMatrix = [
            'Super Admin' => $permissions,
            'Executive Director' => $permissions,
            'Program Manager' => ['manage-programs', 'manage-projects', 'view-me-analytics'],
            'M&E Officer' => ['view-me-analytics', 'conduct-field-monitoring'],
            'Finance Officer' => ['manage-finance-grants'],
            'HR Coordinator' => ['manage-users-roles'],
            'Field Coordinator' => ['conduct-field-monitoring', 'manage-beneficiaries'],
            'Communications Officer' => ['manage-communications'],
            'Zonal Coordinator' => ['manage-zone-level', 'manage-projects', 'conduct-field-monitoring', 'view-assigned-region-only'],
            'LGA Coordinator' => ['manage-lga-level', 'manage-beneficiaries', 'conduct-field-monitoring', 'view-assigned-region-only'],
        ];

        foreach ($roleMatrix as $roleName => $assignedPerms) {
            $role = Role::findOrCreate($roleName, 'web');
            $role->syncPermissions($assignedPerms);
        }

        // 3. Seed Default Super Admin Account for Immediate Testing
        $admin = User::firstOrCreate(
            ['email' => 'admin@innotech.org'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('Password123#'),
                'user_type' => 'staff',
                'is_active' => true,
            ]
        );

        $admin->assignRole('Super Admin');
    }
}