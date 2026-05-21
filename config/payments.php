<?php

return [

    'provider' => env('PAYMENT_PROVIDER', 'paypal'),

    'paypal' => [
        'client_id'     => env('PAYPAL_CLIENT_ID', ''),
        'client_secret' => env('PAYPAL_CLIENT_SECRET', ''),
        'mode'          => env('PAYPAL_MODE', 'sandbox'),
        'currency'      => env('PAYPAL_CURRENCY', 'USD'),
    ],

    'stripe' => [
        'secret_key'     => env('STRIPE_SECRET_KEY', ''),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET', ''),
        'currency'       => env('STRIPE_CURRENCY', 'eur'),
    ],

];
