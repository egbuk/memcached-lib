<?php

namespace HeyMoon\MemcachedLib\Tests;

use HeyMoon\MemcachedLib\Client\Client;
use HeyMoon\MemcachedLib\Config;
use HeyMoon\MemcachedLib\Exception\Exception;

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
    }
}
