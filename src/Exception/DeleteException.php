<?php

namespace HeyMoon\MemcachedLib\Exception;

final class DeleteException extends AbstractResponseException
{
    protected static function getType(): string
    {
        return 'Delete';
    }
}
