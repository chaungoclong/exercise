<?php

use App\Models\Project;

return [
    // Sort Type
    'sort' => [
        'latest' => 0,
        'oldest' => 1,
        'a-z' => 2,
        'z-a' => 3
    ],

    // Role Class
    'role_class' => [
        'admin' => 'badge rounded-pill badge-light-danger',
        'manager' => 'badge rounded-pill badge-light-warning',
        'employee' => 'badge rounded-pill badge-light-success',
        'user_define' => 'badge rounded-pill badge-light-info'
    ],

    // Project Status Class
    'project_status_class' => [
        'on_hold' => 'badge rounded-pill badge-light-info',
        'in_progress' => 'badge rounded-pill badge-light-primary',
        'completed' => 'badge rounded-pill badge-light-success',
        'cancelled' => 'badge rounded-pill badge-light-danger'
    ],

    // Project Status
    'project_status' => [
        Project::STATUS_ON_HOLD => [
            'badge' => 'badge rounded-pill badge-light-info',
            'name' => 'on hold',
            'bg' => 'bg-info'
        ],
        Project::STATUS_CANCELLED => [
            'badge' => 'badge rounded-pill badge-light-danger',
            'name' => 'cancelled',
            'bg' => 'bg-danger'
        ],
        Project::STATUS_IN_PROGRESS => [
            'badge' => 'badge rounded-pill badge-light-primary',
            'name' => 'in progress',
            'bg' => 'bg-primary'
        ],
        Project::STATUS_COMPLETED => [
            'badge' => 'badge rounded-pill badge-light-success',
            'name' => 'completed',
            'bg' => 'bg-success'
        ],
    ]
];
