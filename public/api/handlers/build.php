<?php

use Taria\Response;

$args = $payload['args'] ?? [];

if (count($args) !== 1) {
    Response::json([
        'ok' => false,
        'error' => 'Usage: build <username>'
    ], 400);
    return;
}

$username = $args[0];

Response::json([
    'ok' => true,
    'message' => 'Build command acknowledged',
    'username' => $username
]);
