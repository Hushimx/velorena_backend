<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for WhatsApp service using SmartWats API
    |
    */

    'access_token' => env('WHATSAPP_ACCESS_TOKEN', '68d9d5b40d8e2'),
    
    'instance_id' => env('WHATSAPP_INSTANCE_ID', '68DBB04FECCBA'),
    
    'base_url' => env('WHATSAPP_BASE_URL', 'https://app.smartwats.com/api'),
    
    'webhook_url' => env('WHATSAPP_WEBHOOK_URL', ''),
    
    'webhook_enabled' => env('WHATSAPP_WEBHOOK_ENABLED', false),
    
    'allowed_events' => [
        'received_message',
        'capturer',
        'messages.upsert',
        'contacts.update',
        'contacts.upsert',
        'messages.update',
        'groups.update',
        'new_subscriber'
    ],
    
    'default_country_code' => env('WHATSAPP_DEFAULT_COUNTRY_CODE', '966'),
    
    'timeout' => env('WHATSAPP_TIMEOUT', 30),
    
    'retry_attempts' => env('WHATSAPP_RETRY_ATTEMPTS', 3),
    
    'retry_delay' => env('WHATSAPP_RETRY_DELAY', 1000), // milliseconds
];

