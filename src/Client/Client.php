<?php

namespace HeyMoon\MemcachedLib\Client;

use HeyMoon\MemcachedLib\Contracts\CacheInterface;
use HeyMoon\MemcachedLib\Contracts\ConfigInterface;
use HeyMoon\MemcachedLib\Exception\ConnectException;
use HeyMoon\MemcachedLib\Exception\DeleteException;
use HeyMoon\MemcachedLib\Exception\Exception;
use HeyMoon\MemcachedLib\Exception\WriteException;

class Client extends AbstractClient implements CacheInterface
{
    /**
     * @throws Exception
     */
    public function get(string $key): ?string
    {
        $response = $this->execute(sprintf("get %s\r\n", $this->checkKey($key)));
        preg_match('/VALUE \w+ \d+ (\d+)/m', $response, $matches);
        if (array_key_exists(1, $matches)) {
            return $this->read($matches[1]+1);
        } else {
            return null;
        }
    }

    public function set(string $key, string $value, int $ttl = 0): void
    {
        $length = mb_strlen($value, '8bit');
        $response = $this->execute(sprintf("set %s 0 %d %d\r\n%s\r\n", $this->checkKey($key), $ttl, $length, $value));
        if ($response !== "STORED\r\n") {
            throw new WriteException($response);
        }
    }

    public function delete(string $key): void
    {
        $response = $this->execute(sprintf("delete %s\r\n", $this->checkKey($key)));
        if ($response !== "\r\n") {
            throw new DeleteException($response);
        }
    }

    public function connect(ConfigInterface $config): void
    {
        parent::connect($config);
        if (!stream_set_blocking($this->socket, true)) {
            throw new ConnectException('Failed to switch to blocking mode');
        }
    }
}
