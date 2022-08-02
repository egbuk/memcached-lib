<?php

namespace HeyMoon\MemcachedLib\Contracts;

use HeyMoon\MemcachedLib\Exception\Exception;

interface CacheInterface extends SocketClientInterface
{
    /**
     * @throws Exception
     */
    public function get(string $key): ?string;

    /**
     * @throws Exception
     */
    public function set(string $key, string $value, int $ttl = 0): void;

    /**
     * @throws Exception
     */
    public function delete(string $key): void;
}
