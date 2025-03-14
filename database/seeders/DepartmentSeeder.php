<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Human Resources',
                'description' => 'Responsible for recruiting, onboarding, training, and employee relations.',
            ],
            [
                'name' => 'Information Technology',
                'description' => 'Manages all technology infrastructure, software development, and technical support.',
            ],
            [
                'name' => 'Finance',
                'description' => 'Handles accounting, financial planning, budgeting, and financial reporting.',
            ],
            [
                'name' => 'Marketing',
                'description' => 'Develops and implements marketing strategies to promote products and services.',
            ],
            [
                'name' => 'Operations',
                'description' => 'Oversees day-to-day operations and ensures efficient business processes.',
            ],
            [
                'name' => 'Sales',
                'description' => 'Responsible for generating revenue through direct sales of products and services.',
            ],
            [
                'name' => 'Research & Development',
                'description' => 'Focuses on innovation and development of new products and services.',
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
} 