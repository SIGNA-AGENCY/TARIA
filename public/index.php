<?php
declare(strict_types=1);

/**
 * TARIA Front Controller / Router Script
 *
 * This file has TWO jobs:
 * 1. Let PHP serve real static files (CSS, JS, images)
 * 2. Route everything else through the engine
 */

// --------------------------------------------------
// Serve static files directly (CRITICAL)
// --------------------------------------------------
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;

if ($path !== '/' && is_file($file)) {
    return false; // Let PHP serve the file as-is
}

// --------------------------------------------------
// Bootstrap the system
// --------------------------------------------------
require_once __DIR__ . '/../core/bootstrap.php';

// --------------------------------------------------
// Dispatch request
// --------------------------------------------------
require_once TARIA_ROOT . '/engine/router.php';
