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
            'label' => 'Facility Needs',
            'icon' => 'fas fa-tools',
            'submenu' => [
                [
                    'label' => 'My Requests',
                    'route' => 'facility-needs.index',
                    'permission' => 'view own facility needs',
                ],
                [
                    'label' => 'All Requests',
                    'route' => 'facility-needs.index',
                    'permission' => 'view all facility needs',
                ],
                [
                    'label' => 'Create Request',
                    'route' => 'facility-needs.create',
                    'permission' => 'create facility needs',
                ],
            ],
        ],
        [
            'label' => 'Feedback',
            'icon' => 'fas fa-comment-dots',
            'submenu' => [
                [
                    'label' => 'My Feedbacks',
                    'route' => 'feedbacks.index',
                ],
                [
                    'label' => 'Send Feedback',
                    'route' => 'users.index',
                    'description' => 'Select a user to send feedback to',
                ],
            ],
        ],
        [
            'label' => 'Document Types',
            'icon' => 'fas fa-file-alt',
            'permission' => 'view document types',
            'submenu' => [
                [
                    'label' => 'All Document Types',
                    'route' => 'document-types.index',
                    'permission' => 'view document types',
                ],
                [
                    'label' => 'Add New',
                    'route' => 'document-types.create',
                    'permission' => 'create document types',
                ],
            ],
        ],
        [
            'label' => 'Working Hours',
            'icon' => 'fas fa-clock',
            'submenu' => [
                [
                    'label' => 'My Working Hours',
                    'route' => 'working-hours.index',
                    'permission' => 'view own working hours',
                ],
                [
                    'label' => 'Add Working Hours',
                    'route' => 'working-hours.create',
                    'permission' => 'create working hours',
                ],
                [
                    'label' => 'Monthly Summary',
                    'route' => 'working-hours.monthly',
                    'permission' => 'view own working hours',
                ],
            ],
        ],
        [
            'label' => 'Company Law',
            'icon' => 'fas fa-balance-scale',
            'submenu' => [
                [
                    'label' => 'View Company Law',
                    'route' => 'company-laws.index',
                    'permission' => 'view company law',
                ],
                [
                    'label' => 'Edit Company Law',
                    'route' => 'company-laws.edit',
                    'permission' => 'edit company law',
                ],
            ],
        ],
        [
            'label' => 'Polls',
            'icon' => 'fas fa-poll',
            'submenu' => [
                [
                    'label' => 'All Polls',
                    'route' => 'polls.index',
                ],
                [
                    'label' => 'Create Poll',
                    'route' => 'polls.create',
                    'permission' => 'create polls',
                ],
            ],
        ],
        [
            'label' => 'Settings',
            'route' => 'settings',
            'icon' => 'fas fa-cog',
        ],
    ],
]; 