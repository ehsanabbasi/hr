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
            'submenu' => [
                [
                    'label' => 'All Users',
                    'route' => 'users.index',
                ],
                [
                    'label' => 'Add New',
                    'route' => 'users.create',
                ],
            ],
        ],
        [
            'label' => 'Departments',
            'icon' => 'fas fa-building',
            'submenu' => [
                [
                    'label' => 'All Departments',
                    'route' => 'departments.index',
                ],
                [
                    'label' => 'Add New',
                    'route' => 'departments.create',
                ],
            ],
        ],
        [
            'label' => 'Job Titles',
            'icon' => 'fas fa-briefcase',
            'submenu' => [
                [
                    'label' => 'All Job Titles',
                    'route' => 'job-titles.index',
                ],
                [
                    'label' => 'Add New',
                    'route' => 'job-titles.create',
                ],
            ],
        ],
        [
            'label' => 'Settings',
            'route' => 'settings',
            'icon' => 'fas fa-cog',
        ],
        // Add more menu items as needed
    ],
]; 