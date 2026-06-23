<?php

return [
    'name' => 'OSA Membership System',
    'name_tamil' => 'கிளிநொச்சி / திருவையாறு மகா வித்தியாலயம் பழைய மாணவர் சங்கம்',
    'url' => getenv('APP_URL') ?: 'http://localhost/osa',
    'timezone' => 'Asia/Colombo',
    'debug' => getenv('APP_DEBUG') === 'true',
    'session_timeout' => 3600,
    'csrf_token_name' => '_csrf_token',
    'upload_path' => dirname(__DIR__) . '/storage/uploads',
    'max_upload_size' => 5 * 1024 * 1024,
    'allowed_extensions' => ['jpg', 'jpeg', 'png', 'pdf'],
    'membership_prefix' => 'OSA',
    'receipt_prefix' => 'REC',
    'application_prefix' => 'APP',
];
