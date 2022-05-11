<?php

return [
    'roles' => [
        'index' => [
            ['link' => "home", 'name' => "Home"],
            ['link' => "roles", 'name' => "Roles"],
            ['name' => "Index"]
        ]
    ],
    'permissions' => [
        'index' => [
            ['link' => "home", 'name' => "Home"],
            ['link' => "permissions", 'name' => "Permissions"],
            ['name' => "Index"]
        ]
    ],
    'positions' => [
        'index' => [
            ['link' => "home", 'name' => "Home"],
            ['link' => "positions", 'name' => "Positions"],
            ['name' => "Index"]
        ]
    ],
    'users' => [
        'index' => [
            ['link' => "home", 'name' => "Home"],
            ['name' => "List User"]
        ],
        'show' => [
            ['link' => "home", 'name' => "Home"],
            ['link' => 'users', 'name' => "List User"],
        ]
    ],
];
