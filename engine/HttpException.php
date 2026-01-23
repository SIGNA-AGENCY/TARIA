<?php
// engine/HttpException.php

final class HttpException extends Exception
{
    public int $status;

    public function __construct(int $status, string $message)
    {
        parent::__construct($message, $status);
        $this->status = $status;
    }
}
