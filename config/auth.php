<?php

return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],

    'guards' => [
        'api' => [ //nama middleware
            'driver' => 'jwt',
            'provider' => 'users',
        ],
    ],

    'providers' => [ // untuk mencari letak moddel data emailnya
        'users' => [
            'driver' => 'eloquent',
            'model' => \App\Models\User::class
        ]
    ]
];  