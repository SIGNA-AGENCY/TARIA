<?php
declare(strict_types=1);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');
$uri = $uri === '' ? '/home' : $uri;

$isApi = str_starts_with($uri, '/api');

$base = $isApi
    ? TARIA_ROOT . '/public/api'
    : TARIA_ROOT . '/public/pages';

$path = $isApi
    ? substr($uri, 4) ?: '/status'
    : $uri;

$file = $base . $path . '.php';

$realBase = realpath($base);
$realFile = realpath($file);

if ($realFile === false || strncmp($realFile, $realBase, strlen($realBase)) !== 0) {
    require TARIA_ROOT . '/public/pages/404.php';
    exit;
}

require $realFile;
