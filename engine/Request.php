<?php
// engine/Request.php

final class Request
{
    public string $method;
    public string $path;
    public bool $isApi;

    public array $headers = [];
    public array $query = [];
    public ?array $body = null;
    public string $rawBody;

    public string $ip;
    public float $time;

    public function __construct()
    {
        $this->time   = microtime(true);
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        // Path normalization
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $this->path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $this->path = '/' . trim($this->path, '/');
        if ($this->path === '//') $this->path = '/';

        $this->isApi = str_starts_with($this->path, '/api/');

        // Headers
        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $name = strtolower(str_replace('_', '-', substr($key, 5)));
                $this->headers[$name] = $value;
            }
        }

        $this->query = $_GET ?? [];

        // Body
        $this->rawBody = file_get_contents('php://input') ?: '';

        if ($this->isApi && $this->rawBody !== '') {
            $decoded = json_decode($this->rawBody, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->body = $decoded;
            }
        }

        // IP (simple for now, no infra magic)
        $this->ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
}
