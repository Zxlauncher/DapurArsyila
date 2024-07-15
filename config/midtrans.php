<?php

return [
    // Other service configurations...

    'midtrans' => [
        'server_key' => env('MIDTRANS_SERVER_KEY'),
        'client_key' => env('MIDTRANS_CLIENT_KEY'),
        'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
        'is_sanitized' => env('MIDTRANS_SANITIZE', true),
        'is_3ds' => env('MIDTRANS_3DS', true),
    ],
];
