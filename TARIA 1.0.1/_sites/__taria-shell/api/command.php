<?php
declare(strict_types=1);

session_start();
require_once __DIR__ . '/builder.php';

header('Content-Type: application/json; charset=utf-8');

function out(array $data): void {
  echo json_encode($data);
  exit;
}

function clean_string(string $s): string {
  return trim(preg_replace('/\s+/', ' ', $s));
}

$raw = file_get_contents('php://input');
$payload = json_decode($raw ?: '[]', true);

$input = clean_string((string)($payload['command'] ?? ''));
if ($input === '') {
  out([
    'ok' => false,
    'lines' => [
      ['text' => 'Missing command.', 'class' => 'error']
    ],
    'expect' => ['inputMode' => 'text', 'mask' => false, 'prompt' => 'TARIA>']
  ]);
}

// Routing tokens (safe to use only for idle-mode commands)
$parts = preg_split('/\s+/', $input);
$verb  = strtolower((string)($parts[0] ?? ''));
$arg1  = (string)($parts[1] ?? '');

$cmdLower = strtolower($input);

// -----------------------------
// FLOW STATE (BUILD)
// -----------------------------
$flow = $_SESSION['flow'] ?? null;

if (is_array($flow) && ($flow['name'] ?? '') === 'build') {
  $step = (string)($flow['step'] ?? 'name');

  // CANCEL works inside flows
  if ($cmdLower === 'cancel') {
    unset($_SESSION['flow'], $_SESSION['build']);
    out([
      'ok' => true,
      'lines' => [
        ['text' => 'Cancelled.', 'class' => 'muted']
      ],
      'expect' => ['inputMode' => 'text', 'mask' => false, 'prompt' => 'TARIA>']
    ]);
  }

  $_SESSION['build'] = $_SESSION['build'] ?? [];
  $build = &$_SESSION['build'];

  if ($step === 'name') {
    $name = strtolower(preg_replace('/[^a-z0-9\-]/', '', $input));
    if ($name === '' || strlen($name) < 3) {
      out([
        'ok' => false,
        'lines' => [
          ['text' => 'Invalid name. Use letters/numbers. Min: 3.', 'class' => 'error'],
        ],
        'expect' => ['inputMode' => 'text', 'mask' => false, 'prompt' => 'Name    :']
      ]);
    }

    // Optional: validate against reserved + pattern in builder (if available)
    if (function_exists('taria_validate_node_name')) {
      [$okName, $safeName, $code] = taria_validate_node_name($name);
      if (!$okName) {
        out([
          'ok' => false,
          'lines' => [
            ['text' => 'Invalid name.', 'class' => 'error'],
            "Reason  : {$code}"
          ],
          'expect' => ['inputMode' => 'text', 'mask' => false, 'prompt' => 'Name    :']
        ]);
      }
      $name = $safeName;
    }

    $build['name'] = $name;
    $_SESSION['flow']['step'] = 'email';

    out([
      'ok' => true,
      'lines' => [
        "Node    : {$name}"
      ],
      'expect' => ['inputMode' => 'text', 'mask' => false, 'prompt' => 'Email   :']
    ]);
  }

  if ($step === 'email') {
    $email = $input;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      out([
        'ok' => false,
        'lines' => [
          ['text' => 'Invalid email.', 'class' => 'error'],
        ],
        'expect' => ['inputMode' => 'text', 'mask' => false, 'prompt' => 'Email   :']
      ]);
    }

    $build['email'] = $email;
    $_SESSION['flow']['step'] = 'password';

    out([
      'ok' => true,
      'lines' => [],
      'expect' => ['inputMode' => 'password', 'mask' => true, 'prompt' => 'Password:']
    ]);
  }

  if ($step === 'password') {
    if (strlen($input) < 10) {
      out([
        'ok' => false,
        'lines' => [
          ['text' => 'Password too short. Minimum: 10 characters.', 'class' => 'error'],
        ],
        'expect' => ['inputMode' => 'password', 'mask' => true, 'prompt' => 'Password:']
      ]);
    }

    $build['password'] = $input;
    $_SESSION['flow']['step'] = 'confirm';

    out([
      'ok' => true,
      'lines' => [],
      'expect' => ['inputMode' => 'password', 'mask' => true, 'prompt' => 'Confirm :']
    ]);
  }

  if ($step === 'confirm') {
    if (($build['password'] ?? '') !== $input) {
      $_SESSION['flow']['step'] = 'password';
      unset($build['password']);

      out([
        'ok' => false,
        'lines' => [
          ['text' => 'Passwords do not match.', 'class' => 'error'],
        ],
        'expect' => ['inputMode' => 'password', 'mask' => true, 'prompt' => 'Password:']
      ]);
    }

    $node  = (string)($build['name'] ?? '');
    $email = (string)($build['email'] ?? '');
    $pass  = (string)($build['password'] ?? '');

    [$ok, $data, $code] = taria_build_node($node, $email, $pass);

    if (!$ok) {
      if (in_array($code, ['NAME_UNAVAILABLE','NAME_RESERVED','INVALID_NAME'], true)) {
        $_SESSION['flow']['step'] = 'name';
        unset($build['name']);

        out([
          'ok' => false,
          'lines' => [
            ['text' => 'Build failed.', 'class' => 'error'],
            "Reason  : {$code}"
          ],
          'expect' => ['inputMode' => 'text', 'mask' => false, 'prompt' => 'Name    :']
        ]);
      }

      unset($_SESSION['flow'], $_SESSION['build']);

      out([
        'ok' => false,
        'lines' => [
          ['text' => 'Build failed.', 'class' => 'error'],
          "Reason  : {$code}"
        ],
        'expect' => ['inputMode' => 'text', 'mask' => false, 'prompt' => 'TARIA>']
      ]);
    }

    unset($_SESSION['flow'], $_SESSION['build']);

    $bytes = (int)($data['bytes'] ?? 0);
    $files = (int)($data['files'] ?? 0);
    $ms    = (int)($data['ms'] ?? 0);
    $path  = (string)($data['path'] ?? '');
    $node  = (string)($data['node'] ?? '');

    out([
      'ok' => true,
      'lines' => [
        ['text' => 'Status  : CREATED', 'class' => 'success'],
        "Node    : {$node}",
        "Path    : {$path}",
        "Files   : {$files}",
        "Bytes   : {$bytes}",
        "Time    : {$ms}ms"
      ],
      'expect' => ['inputMode' => 'text', 'mask' => false, 'prompt' => 'TARIA>']
    ]);
  }

  out([
    'ok' => false,
    'lines' => [
      ['text' => 'Flow error.', 'class' => 'error']
    ],
    'expect' => ['inputMode' => 'text', 'mask' => false, 'prompt' => 'TARIA>']
  ]);
}

// -----------------------------
// COMMAND ROUTES (IDLE MODE)
// -----------------------------
if ($verb === 'help') {
  out([
    'ok' => true,
    'lines' => [
      'Commands:',
      '  help',
      '  info',
      '  clear',
      '  build [name]',
      '  cancel'
    ],
    'expect' => ['inputMode' => 'text', 'mask' => false, 'prompt' => 'TARIA>']
  ]);
}

if ($verb === 'info') {
  out([
    'ok' => true,
    'lines' => [
      'TARIA',
      'Mode   : NORMAL',
      'Engine : ONLINE',
    ],
    'expect' => ['inputMode' => 'text', 'mask' => false, 'prompt' => 'TARIA>']
  ]);
}

if ($verb === 'clear') {
  out([
    'ok' => true,
    'action' => 'clear',
    'lines' => [],
    'expect' => ['inputMode' => 'text', 'mask' => false, 'prompt' => 'TARIA>']
  ]);
}

if ($verb === 'build') {
  $_SESSION['flow'] = ['name' => 'build', 'step' => 'name'];
  $_SESSION['build'] = [];

  // Optional: build <name> prefill
  if ($arg1 !== '') {
    $name = strtolower(preg_replace('/[^a-z0-9\-]/', '', $arg1));

    if (function_exists('taria_validate_node_name')) {
      [$okName, $safeName, $code] = taria_validate_node_name($name);
      if (!$okName) {
        out([
          'ok' => false,
          'lines' => [
            ['text' => 'Invalid name.', 'class' => 'error'],
            "Reason  : {$code}",
            "Try     : build <name>"
          ],
          'expect' => ['inputMode' => 'text', 'mask' => false, 'prompt' => 'TARIA>']
        ]);
      }
      $name = $safeName;
    } else {
      if ($name === '' || strlen($name) < 3) {
        out([
          'ok' => false,
          'lines' => [
            ['text' => 'Invalid name.', 'class' => 'error'],
            "Try     : build <name>"
          ],
          'expect' => ['inputMode' => 'text', 'mask' => false, 'prompt' => 'TARIA>']
        ]);
      }
    }

    $_SESSION['build']['name'] = $name;
    $_SESSION['flow']['step'] = 'email';

    out([
      'ok' => true,
      'lines' => [
        'BUILD',
        "Node    : {$name}"
      ],
      'expect' => ['inputMode' => 'text', 'mask' => false, 'prompt' => 'Email   :']
    ]);
  }

  out([
    'ok' => true,
    'lines' => [
      'BUILD'
    ],
    'expect' => ['inputMode' => 'text', 'mask' => false, 'prompt' => 'Name    :']
  ]);
}

if ($verb === 'cancel') {
  out([
    'ok' => true,
    'lines' => [
      ['text' => 'Nothing to cancel.', 'class' => 'muted']
    ],
    'expect' => ['inputMode' => 'text', 'mask' => false, 'prompt' => 'TARIA>']
  ]);
}

out([
  'ok' => false,
  'lines' => [
    ['text' => 'Unknown command. Type help.', 'class' => 'error']
  ],
  'expect' => ['inputMode' => 'text', 'mask' => false, 'prompt' => 'TARIA>']
]);
