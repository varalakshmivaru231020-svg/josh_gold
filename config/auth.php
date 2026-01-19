<?php

return [
    'defaults' => [
        'guard' => 'agent',
        'passwords' => 'agents',
    ],

    'guards' => [
        'agent' => [
            'driver' => 'session',
            'provider' => 'agents',
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
      
       'distributor' => [
        'driver' => 'session',
        'provider' => 'distributors', // reference the provider below
         ],
      ],

    'providers' => [
        'agents' => [
            'driver' => 'eloquent',
            'model' => App\Models\Agent::class,
        ],

        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
      
      'distributors' => [
        'driver' => 'eloquent',
        'model' => App\Models\Distributor::class, // make sure this model exists
    ],
    ],

    'passwords' => [
        'agents' => [
            'provider' => 'agents',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],

        'admins' => [
            'provider' => 'admins',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
        'distributors' => [
          'provider' => 'distributors',
          'table' => 'password_resets',
          'expire' => 60,
           'throttle' => 60,
         ],
       ],

    'password_timeout' => 10800,
]; 