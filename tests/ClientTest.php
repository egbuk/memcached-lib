<?php

namespace HeyMoon\MemcachedLib\Tests;

use HeyMoon\MemcachedLib\Client\Client;
use HeyMoon\MemcachedLib\Client\AbstractClient;
use HeyMoon\MemcachedLib\Config;
use HeyMoon\MemcachedLib\Exception\DeleteException;
use HeyMoon\MemcachedLib\Exception\Exception;
use HeyMoon\MemcachedLib\Exception\InvalidKeyException;

class ClientTest extends AbstractClientTestCase
{
    protected function setUp(): void
    {
        $this->client = new Client();
    }

    /**
     * @throws Exception
     * @covers Client::get
     * @covers Client::set
     * @covers Client::delete
     * @covers AbstractClient::execute
     * @covers AbstractClient::read
     */
    public function testClientMethods()
    {
        /** @var Client $client */
        $client = $this->client;
        $client->connect(Config::parseFromUrl(static::NET_URL));
        $client->set('key', 'value');
        $this->assertEquals('value', $client->get('key'));
        $client->delete('key');
        $this->assertNull($client->get('key'));
        $this->expectException(DeleteException::class);
        $client->delete('key');
    }

    /**
     * @covers AbstractClient::checkKey
     * @covers AbstractClient::sanitizeKey
     * @throws Exception
     */
    public function testKeyCheck()
    {
        /** @var Client $client */
        $client = $this->client;
        $this->expectException(InvalidKeyException::class);
        $client->delete('key with space');
    }
}
