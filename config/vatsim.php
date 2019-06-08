<?php

return [
    'consumer_key' => env('VATSIM_CONSUMER_KEY'),
    'method' => env('VATSIM_SSO_METHOD', 'RSA-SHA1'),
    'callback_url' => env('VATSIM_SSO_CALLBACK_URL'),
    'sso_key' => (file_exists(base_path('.sso.rsa')) ? file_get_contents(base_path('.sso.rsa')) : ''),
];