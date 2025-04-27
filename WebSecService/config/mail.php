<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    |
    | This option controls the default mailer that is used to send all email
    | messages unless another mailer is explicitly specified when sending
    | the message.
    |
    */

    'default' => env('MAIL_MAILER', 'smtp'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    |
    | Here you may configure all of the mailers used by your application.
    | Laravel supports a variety of mail "transport" drivers.
    |
    | Supported: "smtp", "sendmail", "mailgun", "ses", "ses-v2",
    |            "postmark", "resend", "log", "array", "failover", "roundrobin"
    |
    */

    'mailers' => [

        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', 'smtp.office365.com'), // Outlook SMTP server
            'port' => env('MAIL_PORT', 587),                  // SMTP port
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),    // Encryption type
            'username' => env('MAIL_USERNAME'),               // Your email username
            'password' => env('MAIL_PASSWORD'),               // Your email password
            'timeout' => null,
            'auth_mode' => null,
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | Here you may specify a name and address that is used globally for all
    | emails that are sent by your application.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'abdullah230100156@sut.edu.eg'), 
        'name' => env('MAIL_FROM_NAME', 'SUT Service'), 
    ],

];
