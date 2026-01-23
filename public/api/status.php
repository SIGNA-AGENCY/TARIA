<?php
declare(strict_types=1);

echo json_encode([
    'ok'     => true,
    'app'    => 'TARIA',
    'status' => 'online',
    'time'   => time(),
], JSON_UNESCAPED_SLASHES);
