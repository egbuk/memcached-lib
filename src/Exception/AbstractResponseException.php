<?php

namespace HeyMoon\MemcachedLib\Exception;

use Throwable;

abstract class AbstractResponseException extends Exception
{
    public function __construct(string $response = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(static::getType()." exception. Response: $response" . ($response ? '' : '[empty]'), $code, $previous);
    }

    protected abstract static function getType(): string;
}
