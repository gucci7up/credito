<?php

require_once __DIR__ . '/env.php';

env_load(__DIR__ . '/../.env');

return [
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'name' => env('DB_NAME', 'perfumes_pos'),
    'user' => env('DB_USER', 'root'),
    'pass' => env('DB_PASS', ''),
];

