<?php
// core/bootstrap.php

declare(strict_types=1);

// --------------------------------------------------
// Paths
// --------------------------------------------------
define('TARIA_ROOT', dirname(__DIR__));
define('TARIA_CORE', TARIA_ROOT . '/core');
define('TARIA_ENGINE', TARIA_ROOT . '/engine');
define('TARIA_CONFIG', TARIA_ROOT . '/config');
define('TARIA_STORAGE', TARIA_ROOT . '/storage');
define('TARIA_PUBLIC', TARIA_ROOT . '/public');

// --------------------------------------------------
// Environment
// --------------------------------------------------
$app = require TARIA_CONFIG . '/app.php';

define('TARIA_ENV', $app['env'] ?? 'production');

if (TARIA_ENV === 'development') {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}

// --------------------------------------------------
// Guard rails
// --------------------------------------------------
$required = [
    TARIA_ENGINE,
    TARIA_STORAGE,
];

foreach ($required as $path) {
    if (!is_dir($path)) {
        http_response_code(500);
        echo "TARIA boot failure: missing {$path}";
        exit;
    }
}

// --------------------------------------------------
// Autoload (manual, intentional)
// --------------------------------------------------
require TARIA_ENGINE . '/router.php';