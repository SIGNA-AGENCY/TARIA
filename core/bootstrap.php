<?php
declare(strict_types=1);

/**
 * TARIA Bootstrap
 *
 * Purpose:
 * - Define absolute project root
 * - Load core configuration
 * - Register autoloading (manual, deterministic)
 * - Establish global error handling
 *
 * This file must be loaded FIRST.
 */

// --------------------------------------------------
// Absolute root of the TARIA installation
// --------------------------------------------------
define('TARIA_ROOT', dirname(__DIR__));

// --------------------------------------------------
// Load app configuration
// --------------------------------------------------
require_once TARIA_ROOT . '/config/app.php';

// --------------------------------------------------
// Core engine includes (explicit, no magic)
// --------------------------------------------------
require_once TARIA_ROOT . '/engine/HttpException.php';
require_once TARIA_ROOT . '/engine/Request.php';
require_once TARIA_ROOT . '/engine/Response.php';

// --------------------------------------------------
// Global exception handling
// --------------------------------------------------
set_exception_handler(function (Throwable $e) {
    if ($e instanceof HttpException) {
        http_response_code($e->getCode());
        require TARIA_ROOT . '/public/pages/404.php';
        return;
    }

    http_response_code(500);

    if (APP_DEBUG === true) {
        echo '<pre>';
        echo $e;
        echo '</pre>';
        return;
    }

    echo 'Internal Server Error';
});
