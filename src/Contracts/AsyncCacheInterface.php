<?php

namespace HeyMoon\MemcachedLib\Contracts;

interface AsyncCacheInterface extends SocketClientInterface
{
    public function getAsync(string $key, callable $callback): mixed;

    public function setAsync(string $key, mixed $value, ?callable $callback = null);

    public function deleteAsync(string $key, ?callable $callback = null);
}
