<?php

namespace HeyMoon\MemcachedLib\Contracts;

interface CacheInterface extends SocketClientInterface
{
    public function get(string $key): mixed;

    public function set(string $key, mixed $value);

    public function delete(string $key);
}
