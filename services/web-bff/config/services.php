<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'catalog_service_url' => env('CATALOG_SERVICE_URL', 'http://catalog-service'),
    'orders_service_url' => env('ORDERS_SERVICE_URL', 'http://orders-service'),
    'users_service_url' => env('USERS_SERVICE_URL', 'http://users-service'),
    'chatbot_service_url' => env('CHATBOT_SERVICE_URL', 'http://chatbot-service'),

    'jaeger_otlp_url' => env('JAEGER_OTLP_URL', 'http://jaeger:4318'),

];
