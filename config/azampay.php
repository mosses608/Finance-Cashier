<?php

return [
    'client_id' => env('AZAM_PAY_CLIENT_ID'),
    'client_secret' => env('AZAM_PAY_CLIENT_SECRET'),
    'app_name' => env('AZAM_PAY_APP_NAME'),
    'base_url' => env('AZAM_PAY_BASE_URL', 'https://sandbox.azampay.co.tz'),
];
