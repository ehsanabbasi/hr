<?php

return [
    'sidebar' => [
        [
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => 'fas fa-home',
        ],
        [
            'label' => 'Users',
            'icon' => 'fas fa-users',
            'permission' => 'view users',
            'submenu' => [
                [
                    'label' => 'All Users',
                    'route' => 'users.index',
                    'permission' => 'view users',
                ],
                [
                    'label' => 'Add New',
                    'route' => 'users.create',
                    'permission' => 'create users',
                ],
            ],
        ],
        [
            'label' => 'Departments',
            'icon' => 'fas fa-building',
            'permission' => 'view departments',
            'submenu' => [
                [
                    'label' => 'All Departments',
                    'route' => 'departments.index',
                    'permission' => 'view departments',
                ],
                [
                    'label' => 'Add New',
                    'route' => 'departments.create',
                    'permission' => 'create departments',
                ],
            ],
        ],
        [
            'label' => 'Job Titles',
            'icon' => 'fas fa-briefcase',
            'permission' => 'view job titles',
            'submenu' => [
                [
                    'label' => 'All Job Titles',
                    'route' => 'job-titles.index',
                    'permission' => 'view job titles',
                ],
                [
                    'label' => 'Add New',
                    'route' => 'job-titles.create',
                    'permission' => 'create job titles',
                ],
            ],
        ],
        [
            'label' => 'Leave Management',
            'icon' => 'fas fa-calendar-alt',
            'submenu' => [
                [
                    'label' => 'My Leave Requests',
                    'route' => 'leave-requests.index',
                    'permission' => 'view own leave requests'
                ],
                [
                    'label' => 'Leave Approvals',
                    'route' => 'leave-approvals.index',
                    'permission' => 'view department leave requests'
                ],
                [
                    'label' => 'Leave Reasons',
                    'route' => 'leave-reasons.index',
                    'permission' => 'view leave reasons'
                ],
            ]
        ],
        [
            'label' => 'Settings',
            'route' => 'settings',
            'icon' => 'fas fa-cog',
        ],
        // Add more menu items as needed
    ],
]; 