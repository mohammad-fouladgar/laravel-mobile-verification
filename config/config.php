<?php

return [

    'user_table' => 'users',

    'token_table' => 'mobile_verification_tokens',

    'token_length' => 5,

    'token_lifetime' => 5, // min

    'sms_client' => '',

    'routes' => [
        'verify' => '/auth/mobile/verify',
        'resend' => '/auth/mobile/resend',
    ]
];
