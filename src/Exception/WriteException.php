<?php

namespace HeyMoon\MemcachedLib\Exception;

final class WriteException extends AbstractResponseException
{
    protected static function getType(): string
    {
        return 'Write';
    }
}
