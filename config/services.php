<?php

return [

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | WhatsApp OTP Provider
    |--------------------------------------------------------------------------
    | Configuration for the WhatsApp OTP delivery service. When "enabled" is
    | false, or the provider is "log", OTP codes are written to the log file
    | instead of being delivered, keeping the app runnable without a real
    | gateway.
    */
    'whatsapp' => [
        'enabled' => (bool) env('WHATSAPP_OTP_ENABLED', false),
        'provider' => env('WHATSAPP_OTP_PROVIDER', 'log'),
        'api_url' => env('WHATSAPP_API_URL'),
        'api_token' => env('WHATSAPP_API_TOKEN'),
        'sender' => env('WHATSAPP_SENDER'),
        'otp_ttl' => (int) env('WHATSAPP_OTP_TTL', 5),
    ],

];
