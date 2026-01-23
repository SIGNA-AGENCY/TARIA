<?php
// engine/Response.php

final class Response
{
    private int $status = 200;
    private array $headers = [];
    private string $body = '';

    private bool $sent = false;

    public static function json(array $data, int $status = 200): self
    {
        $res = new self();
        $res->status = $status;
        $res->headers['Content-Type'] = 'application/json; charset=utf-8';
        $res->body = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        return $res;
    }

    public static function html(string $html, int $status = 200): self
    {
        $res = new self();
        $res->status = $status;
        $res->headers['Content-Type'] = 'text/html; charset=utf-8';
        $res->body = $html;
        return $res;
    }

    public static function error(int $status, string $message): self
    {
        return self::json([
            'error' => true,
            'status' => $status,
            'message' => $message
        ], $status);
    }

    public function header(string $key, string $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function send(): void
    {
        if ($this->sent) return;
        $this->sent = true;

        http_response_code($this->status);

        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }

        echo $this->body;
        exit;
    }
}
