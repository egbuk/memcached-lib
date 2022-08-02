<?php

namespace HeyMoon\MemcachedLib\Client;

use HeyMoon\MemcachedLib\Contracts\ConfigInterface;
use HeyMoon\MemcachedLib\Contracts\SocketClientInterface;
use HeyMoon\MemcachedLib\Exception\CommandSendException;
use HeyMoon\MemcachedLib\Exception\ConnectException;
use HeyMoon\MemcachedLib\Exception\Exception;
use HeyMoon\MemcachedLib\Exception\InvalidKeyException;
use HeyMoon\MemcachedLib\Exception\NotConnectedException;

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

    /**
     * @throws Exception
     */
    protected function execute(string $command): bool|string
    {
        if (!$this->isConnected()) {
            throw new NotConnectedException;
        }
        if (!fwrite($this->socket, $command)) {
            throw new CommandSendException;
        }
        return $this->read();
    }

    /**
     * @throws NotConnectedException
     */
    protected function read($size = null): bool|string
    {
        if (!$this->isConnected()) {
            throw new NotConnectedException;
        }
        return fgets($this->socket, $size);
    }

    protected function sanitizeKey($key): string
    {
        return preg_replace("/[^\w\d]/", '', $key);
    }

    /**
     * @throws InvalidKeyException
     */
    protected function checkKey($key): string
    {
        if ($this->sanitizeKey($key) !== $key) {
            throw new InvalidKeyException($key);
        }
        return $key;
    }
}
