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
    ], 'google' => [
    'application-credentials' => env('GOOGLE_APPLICATION_CREDENTIALS'),
 ]

    // 'firebase' => [
    //     'api_key' => 'AIzaSyDPsD8_0ESLiFF5UCZTVVIggRt0pjZtcxA',
    //     'auth_domain' => 'foodali.firebaseapp.com',
    //     'database_url' => 'https://foodali.firebaseio.com',
    //     'secret' => 'ca4X8ykJnTpfmrfoHz48mcU7C3t7bGrEqX2lq6xO',
    //     'storage_bucket' => 'foodali.appspot.com',
    // ]
    // apiKey: "AIzaSyDPsD8_0ESLiFF5UCZTVVIggRt0pjZtcxA",
    // authDomain: "foodali.firebaseapp.com",
    // databaseURL: "https://foodali.firebaseio.com",
    // projectId: "foodali",
    // storageBucket: "foodali.appspot.com",
    // messagingSenderId: "117333719736",
    // appId: "1:117333719736:web:22b0f7927fb5ee0c74524d",
    // measurementId: "G-58PZJ6S20C"
];
