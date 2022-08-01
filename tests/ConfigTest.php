<?php

namespace HeyMoon\MemcachedLib\Tests;

use HeyMoon\MemcachedLib\Client\AsyncClient;
use HeyMoon\MemcachedLib\Config;
use HeyMoon\MemcachedLib\Exception\ConnectException;
use HeyMoon\MemcachedLib\Exception\Exception;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /**
     * @throws Exception
     * @covers Client::connect
     */
    public function testConfig()
    {
        $config = Config::parseFromUrl('net:11211');
        $this->assertEquals('net', $config->getHost());
        $this->assertEquals(11211, $config->getPort());
        $this->assertNull($config->getTimeout());
        $config = Config::parseFromUrl('unix:/var/run/memcached/memcached.sock');
        $this->assertEquals('unix:///var/run/memcached/memcached.sock', $config->getHost());
        $this->assertEquals(-1, $config->getPort());
        $this->assertNull($config->getTimeout());
        $config = Config::parseFromUrl('https://heymoon.cc', 100);
        $this->assertEquals('heymoon.cc', $config->getHost());
        $this->assertEquals(-1, $config->getPort());
        $this->assertEquals(100, $config->getTimeout());
    }
}
