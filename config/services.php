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

    'azampay' => [
    'env'            => env('AZAMPAY_ENV', 'sandbox'),
    'app_name'       => env('AZAMPAY_APP_NAME'),
    'client_id'      => env('AZAMPAY_CLIENT_ID'),
    'client_secret'  => env('AZAMPAY_CLIENT_SECRET'),
    // 'api_key'        => env('AZAMPAY_API_KEY'),
    'token_url' => env('AZAMPAY_ENV') === 'production'
        ? 'https://authenticator.azampay.co.tz/AppRegistration/GenerateToken'
        : 'https://authenticator-sandbox.azampay.co.tz/AppRegistration/GenerateToken',
    'ussd_push_url' => env('AZAMPAY_ENV') === 'production'
        ? 'https://checkout.azampay.co.tz/api/v1/Payment/USSDPush'
        : 'https://sandbox.azampay.co.tz/api/v1/Payment/USSDPush',
],

];
