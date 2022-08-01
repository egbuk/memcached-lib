<?php

namespace HeyMoon\MemcachedLib\Client;

use HeyMoon\MemcachedLib\Contracts\CacheInterface;
use HeyMoon\MemcachedLib\Contracts\ConfigInterface;
use HeyMoon\MemcachedLib\Exception\ConnectException;

class Client extends AbstractClient implements CacheInterface
{
    public function get(string $key): mixed
    {
        // TODO: Implement get() method.
    }

    public function set(string $key, mixed $value)
    {
        // TODO: Implement set() method.
    }

    public function delete(string $key)
    {
        // TODO: Implement delete() method.
    }

    public function connect(ConfigInterface $config): void
    {
        parent::connect($config);
        if (!stream_set_blocking($this->socket, true)) {
            throw new ConnectException('Failed to switch to blocking mode');
        }
    }
}
