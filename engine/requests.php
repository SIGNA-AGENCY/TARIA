<?php
declare(strict_types=1);

return [
    'method' => $_SERVER['REQUEST_METHOD'] ?? 'GET',
    'uri'    => parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH),
    'query'  => $_GET,
    'body'   => file_get_contents('php://input'),
    'ip'     => $_SERVER['REMOTE_ADDR'] ?? null,
];
