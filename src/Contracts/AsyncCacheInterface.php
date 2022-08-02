<?php

namespace HeyMoon\MemcachedLib\Contracts;

interface AsyncCacheInterface extends SocketClientInterface
{
    public function getAsync(string $key, callable $callback): mixed;

    public function setAsync(string $key, string $value, int $ttl = 0, ?callable $callback = null);

    public function deleteAsync(string $key, ?callable $callback = null);
}
