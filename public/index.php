<?php
declare(strict_types=1);

require_once __DIR__ . '/../engine/Request.php';
require_once __DIR__ . '/../engine/Response.php';
require_once __DIR__ . '/../engine/HttpException.php';

$request = new Request();

set_exception_handler(function (Throwable $e) use ($request): void {
    if ($e instanceof HttpException) {
        if ($request->isApi) {
            Response::error($e->status, $e->getMessage())->send();
            exit;
        }

        Response::html(
            "<h1>{$e->status}</h1><p>{$e->getMessage()}</p>",
            $e->status
        )->send();
        exit;
    }

    if ($request->isApi) {
        Response::error(500, 'Internal Server Error')->send();
        exit;
    }

    Response::html('<h1>500</h1><p>Something broke.</p>', 500)->send();
    exit;
});

require dirname(__DIR__) . '/core/bootstrap.php';
require __DIR__ . '/../engine/router.php';
