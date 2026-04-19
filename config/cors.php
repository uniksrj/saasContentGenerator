<?php

$defaultOrigins = 'http://localhost:3000,http://127.0.0.1:3000';
$configuredOrigins = (string) env('CORS_ALLOWED_ORIGINS', env('FRONTEND_URL', $defaultOrigins));

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => array_values(array_filter(array_map('trim', explode(',', $configuredOrigins)))),
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
