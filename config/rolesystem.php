<?php

return [
    // truncate table before seed
    'truncate_before_seed' => true,

    // role default for user when register
    'default' => 'employee',

    // seed user for each role
    'seed_user' => true,

    // role structure init
    'roles_structure' => [
        'admin' => [
            'users'         => 'c,r,u,d',
            'projects'      => 'c,r,u,d',
            'reports'       => 'c,r,u,d',
            'project_roles' => 'c,r,u,d',
            'roles'         => 'c,r,u,d',
            'permissions'   => 'c,r,u,d',
        ],
        'manager' => [
            'users'         => 'c,r,u,d',
            'projects'      => 'c,r,u,d',
            'reports'       => 'c,r,u,d',
            'project_roles' => 'c,r,u,d',
        ],
        'employee' => [
            'reports' => 'c,r,u,d',
        ],
    ],

    // map the characters with the full name of the permission
    'abilities_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ],
];
