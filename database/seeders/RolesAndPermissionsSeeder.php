<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User permissions
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Department permissions
            'view departments',
            'create departments',
            'edit departments',
            'delete departments',
            
            // Job title permissions
            'view job titles',
            'create job titles',
            'edit job titles',
            'delete job titles',
            
            // Leave reason permissions
            'view leave reasons',
            'create leave reasons',
            'edit leave reasons',
            'delete leave reasons',
            
            // Leave request permissions
            'view own leave requests',
            'create leave requests',
            'cancel own leave requests',
            
            // Leave approval permissions
            'view department leave requests',
            'approve leave requests',
            'reject leave requests',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Admin role
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());
        
        // Department Head role
        $departmentHeadRole = Role::create(['name' => 'department_head']);
        $departmentHeadRole->givePermissionTo([
            'view own leave requests',
            'create leave requests',
            'cancel own leave requests',
            'view department leave requests',
            'approve leave requests',
            'reject leave requests',
        ]);
        
        // Employee role
        $employeeRole = Role::create(['name' => 'employee']);
        $employeeRole->givePermissionTo([
            'view own leave requests',
            'create leave requests',
            'cancel own leave requests',
        ]);
        
        // Assign admin role to user ID 1 (assuming this is your admin user)
        $user = \App\Models\User::find(1);
        if ($user) {
            $user->assignRole('admin');
        }
    }
}