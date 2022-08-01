<?php

namespace HeyMoon\MemcachedLib\Contracts;

use HeyMoon\MemcachedLib\Exception\ConnectException;

interface SocketClientInterface
{
    /**
     * @throws ConnectException
     */
    public function connect(ConfigInterface $config): void;

    public function disconnect(): bool;

    public function isConnected(): bool;
}
