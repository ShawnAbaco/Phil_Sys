<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PHPMailer Settings
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for PHPMailer email sending.
    | You can set these values in your .env file.
    |
    */

    'host' => env('MAIL_HOST', 'smtp.gmail.com'),
    'port' => env('MAIL_PORT', 587),
    'username' => env('MAIL_USERNAME'),
    'password' => env('MAIL_PASSWORD'),
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    'from_address' => env('MAIL_FROM_ADDRESS', 'noreply@example.com'),
    'from_name' => env('MAIL_FROM_NAME', 'National ID System'),
    'debug' => env('MAIL_DEBUG', 0), // 0 = off, 1 = client, 2 = client and server
];