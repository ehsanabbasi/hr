<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\JobTitle;
use Illuminate\Database\Seeder;

class JobTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all departments
        $departments = Department::all();

        // Define job titles for each department
        $departmentJobTitles = [
            'Human Resources' => [
                'HR Manager',
                'HR Specialist',
                'Recruiter',
                'Training Coordinator',
                'Employee Relations Specialist',
            ],
            'Information Technology' => [
                'IT Director',
                'Software Developer',
                'System Administrator',
                'Network Engineer',
                'Database Administrator',
                'IT Support Specialist',
                'QA Engineer',
            ],
            'Finance' => [
                'Finance Director',
                'Accountant',
                'Financial Analyst',
                'Payroll Specialist',
                'Auditor',
            ],
            'Marketing' => [
                'Marketing Director',
                'Marketing Specialist',
                'Content Writer',
                'Social Media Manager',
                'Graphic Designer',
                'SEO Specialist',
            ],
            'Operations' => [
                'Operations Manager',
                'Project Manager',
                'Business Analyst',
                'Quality Assurance Specialist',
                'Logistics Coordinator',
            ],
            'Sales' => [
                'Sales Director',
                'Sales Representative',
                'Account Manager',
                'Sales Analyst',
                'Customer Success Manager',
            ],
            'Research & Development' => [
                'R&D Director',
                'Research Scientist',
                'Product Developer',
                'Innovation Specialist',
                'UX Researcher',
            ],
        ];

        // Create job titles for each department
        foreach ($departments as $department) {
            $titles = $departmentJobTitles[$department->name] ?? [];
            
            foreach ($titles as $title) {
                JobTitle::create([
                    'name' => $title,
                    'description' => "Responsible for {$title} duties in the {$department->name} department.",
                    'department_id' => $department->id,
                ]);
            }
        }
    }
} 