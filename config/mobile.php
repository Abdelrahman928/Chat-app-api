<?php

return [
    'vonage' => [
        'key' => env('VONAGE_KEY'),
        'secret' => env('VONAGE_SECRET'),
        'from' => env('VONAGE_FROM'),
        'max_attempts' => (int)env('VERIFICATION_MAX_ATTEMPTS'),
        'time_limit' => (int)env('VERIFICATION_EXPIRATION_TIME_IN_MINUTES'),
    ],
];
