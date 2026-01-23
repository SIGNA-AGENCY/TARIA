<?php
declare(strict_types=1);

require_once __DIR__ . '/../../engine/Response.php';

/**
 * Home = TARIA Shell Loader
 *
 * Why:
 * - `/` is the control surface
 * - Shell is the UI for the OS
 * - MVP shell lives outside engine tree (by design)
 */

// --------------------------------------------------
// Absolute path to MVP shell
// --------------------------------------------------
$shell = TARIA_ROOT . '/public/shell/index.html';

// --------------------------------------------------
// Fail loudly if shell is missing
// --------------------------------------------------
if (!is_file($shell)) {
    Response::html('TARIA shell not found', 500)->send();
    exit;
}

// --------------------------------------------------
// Serve shell
// --------------------------------------------------
readfile($shell);
