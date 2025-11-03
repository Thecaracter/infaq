<?php

return [
    'fontee' => [
        'base_url' => env('FONTEE_BASE_URL', 'https://api.fonnte.com'),
        'api_key' => env('FONTEE_API_KEY'),
        'device_id' => env('FONTEE_DEVICE_ID'),
    ],

    'settings' => [
        'reminder_days' => [
            'first_reminder' => 7,
            'second_reminder' => 30,
            'third_reminder' => 60,
        ],
        'monthly_limit' => 1000,
        'message_delay' => 1,
    ]
];