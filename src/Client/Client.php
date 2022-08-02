<?php

namespace HeyMoon\MemcachedLib\Client;

use HeyMoon\MemcachedLib\Contracts\CacheInterface;
use HeyMoon\MemcachedLib\Contracts\ConfigInterface;
use HeyMoon\MemcachedLib\Exception\ConnectException;
use HeyMoon\MemcachedLib\Exception\Exception;
use HeyMoon\MemcachedLib\Exception\NotConnectedException;

class Client extends AbstractClient implements CacheInterface
{
    /**
     * @throws Exception
     */
    public function get(string $key): ?string
    {
        $result = $this->execute(sprintf("get %s\r\n", $key));
        preg_match('/VALUE \w+ \d+ (\d+)/m', $result, $matches);
        if (array_key_exists(1, $matches)) {
            return $this->read($matches[1]+1);
        } else {
            return null;
        }
    }

    public function set(string $key, string $value, int $ttl = 0)
    {
        $length = mb_strlen($value, '8bit');
        $this->execute(sprintf("set %s 0 %d %d\r\n%s\r\n", $key, $ttl, $length, $value));
    }

    public function delete(string $key)
    {
        $this->execute(sprintf("delete %s\r\n", $key));
    }

    public function connect(ConfigInterface $config): void
    {
        parent::connect($config);
        if (!stream_set_blocking($this->socket, true)) {
            throw new ConnectException('Failed to switch to blocking mode');
        }
    }
}
