<?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    // Custom guard backed by the session (JWT + user payload from users-service),
    // registered in AppServiceProvider::boot(). No local users table.
    'guards' => [
        'web' => [
            'driver' => 'session-jwt',
        ],
    ],

    'providers' => [],

    'passwords' => [],

    'password_timeout' => 10800,

];
