<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\JobTitle;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if it doesn't exist
        $adminEmail = 'admin@example.com';
        if (!User::where('email', $adminEmail)->exists()) {
            $adminUser = User::create([
                'name' => 'Admin User',
                'email' => $adminEmail,
                'password' => Hash::make('password'),
            ]);
        } else {
            $adminUser = User::where('email', $adminEmail)->first();
        }

        // Get all departments and job titles
        $departments = Department::all();
        $jobTitles = JobTitle::all();

        // Create department heads
        foreach ($departments as $department) {
            // Find a job title for this department that includes "Director" or "Manager"
            $headTitle = $jobTitles->where('department_id', $department->id)
                ->filter(function ($title) {
                    return str_contains($title->name, 'Director') || str_contains($title->name, 'Manager');
                })
                ->first();

            if ($headTitle) {
                $headEmail = strtolower(str_replace(' ', '.', $department->name)) . '.head@example.com';
                
                // Check if this department head already exists
                if (!User::where('email', $headEmail)->exists()) {
                    $departmentHead = User::create([
                        'name' => "Head of {$department->name}",
                        'email' => $headEmail,
                        'password' => Hash::make('password'),
                        'department_id' => $department->id,
                        'job_title_id' => $headTitle->id,
                    ]);

                    // Set this user as the head of the department
                    $department->update(['head_id' => $departmentHead->id]);
                } else if (!$department->head_id) {
                    // If department head exists but not set as head
                    $existingHead = User::where('email', $headEmail)->first();
                    $department->update(['head_id' => $existingHead->id]);
                }
            }
        }

        // Create regular employees for each department
        foreach ($departments as $department) {
            // Get job titles for this department
            $departmentJobTitles = $jobTitles->where('department_id', $department->id);
            
            // Create 3-5 employees per department
            $employeeCount = rand(3, 5);
            
            for ($i = 1; $i <= $employeeCount; $i++) {
                $employeeEmail = "employee{$i}." . strtolower(str_replace(' ', '.', $department->name)) . '@example.com';
                
                // Check if this employee already exists
                if (User::where('email', $employeeEmail)->exists()) {
                    continue;
                }
                
                // Randomly select a job title that's not a Director or Manager
                $regularTitles = $departmentJobTitles->filter(function ($title) {
                    return !str_contains($title->name, 'Director') && !str_contains($title->name, 'Manager');
                });
                
                if ($regularTitles->count() > 0) {
                    $regularTitle = $regularTitles->random();
                    
                    User::create([
                        'name' => "Employee {$i} {$department->name}",
                        'email' => $employeeEmail,
                        'password' => Hash::make('password'),
                        'department_id' => $department->id,
                        'job_title_id' => $regularTitle->id,
                    ]);
                }
            }
        }
    }
} 