<?php
declare(strict_types=1);

require_once __DIR__ . '/../../engine/Response.php';

Response::json([
    'name'   => 'TARIA',
    'status' => 'online',
    'time'   => time(),
], 200)->send();
