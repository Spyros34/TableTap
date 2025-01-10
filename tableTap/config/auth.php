<?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'owners',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'owners',
        ],
        // New guard for kitchen
        'kitchen' => [
            'driver' => 'session',
            'provider' => 'kitchens',
        ],
    ],

    'providers' => [
        'owners' => [
            'driver' => 'eloquent',
            'model' => App\Models\Owner::class,
        ],
        // New provider for kitchen
        'kitchens' => [
            'driver' => 'eloquent',
            'model' => App\Models\Kitchen::class,
        ],
    ],

    'passwords' => [
        'owners' => [
            'provider' => 'owners',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
        'kitchens' => [
            'provider' => 'kitchens',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],
];