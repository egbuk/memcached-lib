<?php

namespace HeyMoon\MemcachedLib\Client;

use HeyMoon\MemcachedLib\Contracts\ConfigInterface;
use HeyMoon\MemcachedLib\Contracts\SocketClientInterface;
use HeyMoon\MemcachedLib\Exception\ConnectException;

abstract class AbstractClient implements SocketClientInterface
{
    /**
     * @var resource
     */
    protected $socket;

    public function connect(ConfigInterface $config): void
    {
        $this->disconnect();
        set_error_handler(fn() => null, E_WARNING);
        $this->socket = fsockopen($config->getHost(), $config->getPort(), $errorCode, $errorMessage, $config->getTimeout());
        restore_error_handler();
        if (!$this->isConnected()) {
            throw new ConnectException($errorMessage, $errorCode);
        }
    }

    public function disconnect(): bool
    {
        if ($this->isConnected()) {
            return fclose($this->socket);
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isConnected(): bool
    {
        return is_resource($this->socket);
    }
}
