<?php

return [
    'allow-credentiails' => env('CORS_ALLOW_CREDENTIAILS', false), // set "Access-Control-Allow-Credentials" 👉 string "false" or "true".
    'allow-headers'      => ['*'], // ex: Content-Type, Accept, X-Requested-With
    'expose-headers'     => [],
    'origins'            => ['*'], // ex: http://localhost
    'methods'            => ['*'], // ex: GET, POST, PUT, PATCH, DELETE
    'max-age'            => env('CORS_ACCESS_CONTROL_MAX_AGE', 0),
    'laravel'            => [
        'allow-route-perfix' => env('CORS_LARAVEL_ALLOW_ROUTE_PERFIX', '*'), // The perfix is using \Illumante\Http\Request::is method. 👉
        'route-group-mode'   => env('CORS_LARAVEL_ROUTE_GROUP_MODE', false),
    ],
];
