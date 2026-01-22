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
    ? substr($uri, 4) ?: '/index'
    : $uri;

$file = $base . $path . '.php';

// Guard rails
$realBase = realpath($base);
$realFile = realpath($file);

if ($realFile === false || strncmp($realFile, $realBase, strlen($realBase)) !== 0) {
    http_response_code(404);
    echo $isApi ? json_encode(['error' => 'Not found']) : 'TARIA: route not found';
    exit;
}

if (!is_file($realFile)) {
    http_response_code(404);
    echo $isApi ? json_encode(['error' => 'Not found']) : 'TARIA: route not found';
    exit;
}

if ($isApi) {
    header('Content-Type: application/json; charset=utf-8');
}

require $realFile;
