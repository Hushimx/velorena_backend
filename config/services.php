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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
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

    'freepik' => [
        'api_key' => env('FREEPIK_API_KEY'),
        'base_url' => env('FREEPIK_BASE_URL', 'https://api.freepik.com/v1'),
    ],

    'zoom' => [
        'client_id' => env('ZOOM_CLIENT_ID'),
        'client_secret' => env('ZOOM_CLIENT_SECRET'),
        'account_id' => env('ZOOM_ACCOUNT_ID'),
    ],

    'tap' => [
        'secret_key' => env('TAP_SECRET_KEY'), // Legacy - use test_secret_key or live_secret_key
        'test_secret_key' => env('TAP_TEST_SECRET_KEY'),
        'live_secret_key' => env('TAP_LIVE_SECRET_KEY'),
        'public_key' => env('TAP_PUBLIC_KEY'),
        'test_mode' => env('TAP_TEST_MODE', true),
    ],

];
