<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RemainingPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define remaining permissions based on models not covered in RolesAndPermissionsSeeder
        $permissions = [
            // Facility Needs
            'view facility needs',
            'create facility needs',
            'update facility need status',
            'delete facility needs',
            
            // Feedback
            'view feedbacks',
            'create feedback',
            'edit feedback',
            'delete feedback',
            
            // Company Laws
            'view company laws',
            'create company laws',
            'edit company laws',
            'delete company laws',
            
            // Working Hours
            'view own working hours',
            'create working hours',
            'edit working hours',
            'delete working hours',
            'view all working hours',
            
            // Polls
            'view polls',
            'create polls',
            'edit polls',
            'delete polls',
            'vote in polls',
            'view poll results',
            
            // Surveys
            'view surveys',
            'create surveys',
            'edit surveys',
            'delete surveys',
            'participate in surveys',
            'view survey results',
            
            // Onboarding Tasks
            'view onboarding tasks',
            'create onboarding tasks',
            'edit onboarding tasks',
            'delete onboarding tasks',
            'assign onboarding tasks',
            'complete onboarding tasks',
            
            // Career Opportunities
            'view career opportunities',
            'create career opportunities',
            'edit career opportunities',
            'delete career opportunities',
            'apply to career opportunities',
            'view career opportunity applications',
            'manage career opportunity applications',
            
            // Certificates
            'view certificates',
            'create certificates',
            'edit certificates',
            'delete certificates',
            'view own certificates',
            'issue certificates',
            
            // User Certificates
            'view user certificates',
            'assign certificates',
            'revoke certificates',
            
            // Notifications
            'view notifications',
            'mark notifications as read',
            'send notifications',
            'delete notifications',
            
            // User Settings
            'view user settings',
            'update user settings',
            
            // Invitations
            'create invitations',
            'view invitations',
            'delete invitations',
            
            // Job Management
            'view jobs',
            'create jobs',
            'edit jobs',
            'delete jobs',
            'apply to jobs',
            'view job applications',
            'manage job applications',
        ];

        // Create permissions if they don't exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Find existing roles
        $adminRole = Role::where('name', 'admin')->first();
        $departmentHeadRole = Role::where('name', 'department_head')->first();
        $employeeRole = Role::where('name', 'employee')->first();
        $hrRole = Role::where('name', 'hr')->first();
        $userRole = Role::where('name', 'user')->first();

        // Admin already has all permissions through the original seeder

        // Assign relevant permissions to department head
        if ($departmentHeadRole) {
            $departmentHeadRole->givePermissionTo([
                'view facility needs',
                'update facility need status',
                'view feedbacks',
                'view company laws',
                'view all working hours',
                'view polls',
                'view poll results',
                'view surveys',
                'view survey results',
                'view onboarding tasks',
                'assign onboarding tasks',
                'view career opportunities',
                'view career opportunity applications',
                'view certificates',
                'view user certificates',
                'view notifications',
                'mark notifications as read',
                'send notifications',
                'view user settings',
            ]);
        }

        // Assign relevant permissions to HR
        if ($hrRole) {
            $hrRole->givePermissionTo([
                'view facility needs',
                'create facility needs',
                'update facility need status',
                'delete facility needs',
                'view feedbacks',
                'view company laws',
                'create company laws',
                'edit company laws',
                'delete company laws',
                'view all working hours',
                'view polls',
                'create polls',
                'edit polls',
                'delete polls',
                'view poll results',
                'view surveys',
                'create surveys',
                'edit surveys',
                'delete surveys',
                'view survey results',
                'view onboarding tasks',
                'create onboarding tasks',
                'edit onboarding tasks',
                'delete onboarding tasks',
                'assign onboarding tasks',
                'view career opportunities',
                'create career opportunities',
                'edit career opportunities',
                'delete career opportunities',
                'view career opportunity applications',
                'manage career opportunity applications',
                'view certificates',
                'create certificates',
                'edit certificates',
                'delete certificates',
                'view user certificates',
                'assign certificates',
                'revoke certificates',
                'view notifications',
                'mark notifications as read',
                'send notifications',
                'view user settings',
                'create invitations',
                'view invitations',
                'delete invitations',
                'view jobs',
                'create jobs',
                'edit jobs',
                'delete jobs',
                'view job applications',
                'manage job applications',
            ]);
        }

        // Assign relevant permissions to employee
        if ($employeeRole) {
            $employeeRole->givePermissionTo([
                'view facility needs',
                'create facility needs',
                'create feedback',
                'view company laws',
                'view own working hours',
                'create working hours',
                'edit working hours',
                'delete working hours',
                'view polls',
                'vote in polls',
                'view surveys',
                'participate in surveys',
                'view onboarding tasks',
                'complete onboarding tasks',
                'view career opportunities',
                'apply to career opportunities',
                'view certificates',
                'view own certificates',
                'view notifications',
                'mark notifications as read',
                'view user settings',
                'update user settings',
                'view jobs',
                'apply to jobs',
            ]);
        }

        // Assign relevant permissions to regular user
        if ($userRole) {
            $userRole->givePermissionTo([
                'view company laws',
                'view polls',
                'vote in polls',
                'view surveys',
                'participate in surveys',
                'view career opportunities',
                'apply to career opportunities',
                'view notifications',
                'mark notifications as read',
                'view user settings',
                'update user settings',
                'view jobs',
                'apply to jobs',
            ]);
        }
    }
} 