<?php
declare(strict_types=1);

final class Request
{
    public function __construct(
        public readonly string $method,
        public readonly string $uri,
        public readonly array  $query,
        public readonly string $body,
        public readonly array  $headers,
    ) {}

    public static function fromGlobals(): self
    {
        $method  = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri     = $_SERVER['REQUEST_URI'] ?? '/';
        $query   = $_GET ?? [];
        $body    = file_get_contents('php://input') ?: '';
        $headers = function_exists('getallheaders') ? getallheaders() : [];

        return new self(
            method: $method,
            uri: $uri,
            query: $query,
            body: $body,
            headers: $headers
        );
    }

    public function json(): array
    {
        if ($this->body === '') {
            return [];
        }

        $data = json_decode($this->body, true);
        return is_array($data) ? $data : [];
    }
}
