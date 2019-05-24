<?php

return [
    'method' => env('VATSIM_SSO_METHOD', 'RSA'),
    'callback_url' => env('VATSIM_SSO_CALLBACK_URL'),
    'sso_key' => env('VATSIM_SSO_RSA'),
];