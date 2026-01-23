<?php
declare(strict_types=1);

require_once __DIR__ . '/../../engine/Response.php';

/**
 * Command API
 *
 * Receives CLI commands and returns structured output.
 * Stateless. Synchronous. Deterministic.
 */

// --------------------------------------------------
// Read raw JSON input
// --------------------------------------------------
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

// --------------------------------------------------
// Validate input
// --------------------------------------------------
$command = trim($data['command'] ?? '');

if ($command === '') {
    Response::json([
        'ok' => false,
        'output' => 'No command provided'
    ], 400)->send();
    exit;
}

// --------------------------------------------------
// Command router (temporary)
// --------------------------------------------------
switch ($command) {
    case 'help':
        $output = <<<TXT
Available commands:
  help        Show this message
  version     Show system version
TXT;
        break;

    case 'version':
        $output = 'TARIA OS v0.0.2';
        break;

    default:
        $output = "Unknown command: {$command}";
        break;
}

// --------------------------------------------------
// Respond
// --------------------------------------------------
Response::json([
    'ok' => true,
    'output' => $output
])->send();
