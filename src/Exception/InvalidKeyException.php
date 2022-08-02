<?php

namespace HeyMoon\MemcachedLib\Exception;

use Throwable;

class InvalidKeyException extends Exception
{
    public function __construct(string $key = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("Invalid key: $key", $code, $previous);
    }
}
