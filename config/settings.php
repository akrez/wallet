<?php

return [
    'delete_deprecated_payment' => [
        'after_hours' => 20,
        'count' => 20,
    ],
    'pagination' => [
        'default_per_page' => 20,
    ],
    'navasan' => [
        'api_key' => env('NAVASAN_API_KEY'),
        'base_url' => 'https://api.navasan.tech/latest/',
    ],
];
