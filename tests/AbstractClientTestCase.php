<?php

namespace HeyMoon\MemcachedLib\Tests;

use HeyMoon\MemcachedLib\Config;
use HeyMoon\MemcachedLib\Contracts\SocketClientInterface;
use HeyMoon\MemcachedLib\Exception\ConnectException;
use HeyMoon\MemcachedLib\Exception\Exception;
use PHPUnit\Framework\TestCase;

abstract class AbstractClientTestCase extends TestCase
{
    protected SocketClientInterface $client;

    /**
     * @throws Exception
     * @covers Client::connect
     */
    public function testConnect()
    {
        $this->assertFalse($this->client->isConnected());
        $this->client->connect(Config::parseFromUrl('net:11211'));
        $this->assertTrue($this->client->isConnected());
        $this->client->disconnect();
        $this->assertFalse($this->client->isConnected());
        $this->client->connect(Config::parseFromUrl('unix:/var/run/memcached/memcached.sock'));
        $this->assertTrue($this->client->isConnected());
        $this->expectException(ConnectException::class);
        $this->client->connect(Config::parseFromUrl('https://heymoon.cc'));
    }
}
