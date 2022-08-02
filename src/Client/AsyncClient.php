<?php

namespace HeyMoon\MemcachedLib\Client;

use HeyMoon\MemcachedLib\Contracts\AsyncCacheInterface;
use HeyMoon\MemcachedLib\Contracts\ConfigInterface;
use HeyMoon\MemcachedLib\Exception\ConnectException;

class AsyncClient extends AbstractClient implements AsyncCacheInterface
{
    public function getAsync(string $key, callable $callback): mixed
    {
        // TODO: Implement getAsync() method.
    }

    public function setAsync(string $key, string $value, int $ttl = 0, ?callable $callback = null)
    {
        // TODO: Implement setAsync() method.
    }

    public function deleteAsync(string $key, ?callable $callback = null)
    {
        // TODO: Implement deleteAsync() method.
    }

    public function connect(ConfigInterface $config): void
    {
        parent::connect($config);
        if (!stream_set_blocking($this->socket, false)) {
            throw new ConnectException('Failed to switch to non-blocking mode');
        }
    }
}
