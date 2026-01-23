<?php
declare(strict_types=1);

/**
 * TARIA Router
 *
 * Responsibility:
 * - Resolve URI → filesystem
 * - Enforce public boundary
 * - Dispatch exactly one file
 *
 * Router does NOT render.
 * Router does NOT catch.
 * Router only decides.
 */

use Engine\HttpException;

// --------------------------------------------------
// Normalize URI
// --------------------------------------------------
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');

// Root resolves to /home
if ($uri === '') {
    $uri = '/home';
}

// --------------------------------------------------
// Determine surface
// --------------------------------------------------
$isApi = str_starts_with($uri, '/api');

// --------------------------------------------------
// Resolve base directory
// --------------------------------------------------
$baseDir = $isApi
    ? TARIA_ROOT . '/public/api'
    : TARIA_ROOT . '/public/pages';

// --------------------------------------------------
// Resolve relative path
// --------------------------------------------------
if ($isApi) {
    // Strip `/api`
    $path = substr($uri, 4);
    $path = $path === '' ? '/index' : $path;
} else {
    $path = $uri;
}

// --------------------------------------------------
// Build target file
// --------------------------------------------------
$file = $baseDir . $path . '.php';

// --------------------------------------------------
// Security: realpath boundary enforcement
// --------------------------------------------------
$realBase = realpath($baseDir);
$realFile = realpath($file);

if ($realBase === false || $realFile === false) {
    throw new HttpException(404, 'Route not found');
}

if (strncmp($realFile, $realBase, strlen($realBase)) !== 0) {
    throw new HttpException(404, 'Invalid route');
}

// --------------------------------------------------
// Dispatch
// --------------------------------------------------
require $realFile;
