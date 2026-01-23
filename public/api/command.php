<?php
declare(strict_types=1);

require_once __DIR__ . '/../../engine/Response.php';
require_once __DIR__ . '/../../engine/Request.php';
require_once __DIR__ . '/../../engine/CommandRegistry.php';

/**
 * Command API
 *
 * Receives CLI commands and returns structured output.
 * Execution is synchronous and deterministic.
 */

$request = Request::fromGlobals();
$data    = $request->json();

$command = trim((string) ($data['command'] ?? ''));

if ($command === '') {
    Response::json([
        'ok'     => false,
        'output' => 'No command provided'
    ], 400)->send();
    exit;
}

if (!CommandRegistry::exists($command)) {
    Response::json([
        'ok'     => false,
        'output' => "Unknown command: {$command}"
    ], 400)->send();
    exit;
}

switch ($command) {
    case 'help':
        $output = CommandRegistry::helpText();
        break;

    case 'version':
        $output = 'TARIA OS v0.0.2';
        break;

    default:
        // This should never be reached because of exists() check
        $output = 'Command registered but not implemented';
        break;
}

Response::json([
    'ok'     => true,
    'output' => $output
])->send();
