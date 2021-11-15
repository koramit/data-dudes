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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'SUBHANNAH_API_NAME' => env('SUBHANNAH_API_NAME'),
    'SUBHANNAH_API_TOKEN' => env('SUBHANNAH_API_TOKEN'),
    'SUBHANNAH_API_URL' => env('SUBHANNAH_API_URL'),

    'STAY_TOKEN' => env('STAY_TOKEN'),
    'STAY_QUEUE_URL' => env('STAY_QUEUE_URL'),
    'STAY_STATUS_URL' => env('STAY_STATUS_URL'),
    'STAY_STATUS_NOTES_URL' => env('STAY_STATUS_NOTES_URL'),
    'STAY_OUTCOME_URL' => env('STAY_OUTCOME_URL'),
    'STAY_OUTCOME_NOTES_URL' => env('STAY_OUTCOME_NOTES_URL'),

    'API_GUARD' => [
        'app' => env('API_GUARD_APP'),
        'token' => env('API_GUARD_TOKEN'),
    ],

];
