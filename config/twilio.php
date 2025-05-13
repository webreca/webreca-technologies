<?php

return [
    'api_keys' => [
        'sid' => env('TWILIO_SID', null),
        'token' => env('TWILIO_TOKEN', null),
        'from' => env('TWILIO_FROM', null)
    ]
];
