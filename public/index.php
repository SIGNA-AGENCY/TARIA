<?php
declare(strict_types=1);

/**
 * 1. Load engine primitives FIRST
 *    (they must exist for error handling)
 */
require_once __DIR__ . '/../engine/Request.php';
require_once __DIR__ . '/../engine/Response.php';
require_once __DIR__ . '/../engine/HttpException.php';

/**
 * 2. Create request
 */
$request = new Request();

/**
 * 3. Register exception handler
 *    (NOW the system can fail safely)
 */
set_exception_handler(function (Throwable $e) use ($request) {
    if ($e instanceof HttpException) {
        if ($request->isApi) {
            Response::error($e->status, $e->getMessage())->send();
        }

        Response::html(
            "<h1>{$e->status}</h1><p>{$e->getMessage()}</p>",
            $e->status
        )->send();
    }

    if ($request->isApi) {
        Response::error(500, 'Internal Server Error')->send();
    }

    Response::html(
        '<h1>500</h1><p>Something broke.</p>',
        500
    )->send();
});

/**
 * 4. ONLY NOW load bootstrap
 */
require dirname(__DIR__) . '/core/bootstrap.php';
