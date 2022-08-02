<?php

namespace HeyMoon\MemcachedLib\Contracts;

interface AsyncCacheInterface extends SocketClientInterface
{
    public function getAsync(string $key, callable $callback): void;

    public function setAsync(string $key, string $value, int $ttl = 0, ?callable $callback = null): void;

    public function deleteAsync(string $key, ?callable $callback = null): void;

    public function isRunning(): bool;
}
