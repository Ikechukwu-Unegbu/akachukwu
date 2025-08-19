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
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'digitalocean' => [
        'key'                 => env('DO_ACCESS_KEY_ID', 'DO00ENHCQJ4RYJF6Q48T'),
        'secret'              => env('DO_SECRET_ACCESS_KEY', 'YpPeWuLtha7svFKXdYujELbEDfnWut2sTdphxR0GDvQ'),
        'region'              => env('DO_DEFAULT_REGION', 'fra1'),
        'bucket'              => env('DO_BUCKET', 'vastel-uploads'),
        'url'                 => env('DO_URL', 'https://fra1.digitaloceanspaces.com'),
        'endpoint'            => env('DO_ENDPOINT', 'https://fra1.digitaloceanspaces.com'),
        'use_path_style'      => env('DO_USE_PATH_STYLE_ENDPOINT', true),
        'cdn'                 => env('DO_CDN', 'https://vastel-uploads.fra1.digitaloceanspaces.com'),
        'folder'              => env('DO_FOLDER', 'production'),
    ],
    'onesignal' => [
        'app_id' => env('ONESIGNAL_APP_ID'),
        'rest_api_url' => env('ONESIGNAL_REST_API_URL', 'https://api.onesignal.com'),
        'rest_api_key' => env('ONESIGNAL_REST_API_KEY'),
        'guzzle_client_timeout' => env('ONESIGNAL_GUZZLE_CLIENT_TIMEOUT', 0),
    ],
    'branch' => [
        'key' => env('BRANCH_KEY'),
        'url' => env('BRANCH_API_URL', 'https://api2.branch.io/v1/url'),
    ],

    'quidax' => [
        'api_key' => env('QUIDAX_API_KEY'),
        'secret_key' => env('QUIDAX_SECRET_KEY'),
        'public_key' => env('QUIDAX_PUBLIC_KEY'),
        'test_mode' => env('QUIDAX_TEST_MODE', false),
    ],

];
