<?php

namespace HeyMoon\MemcachedLib\Tests;

use HeyMoon\MemcachedLib\Client\AsyncClient;
use HeyMoon\MemcachedLib\Config;
use HeyMoon\MemcachedLib\Exception\DeleteException;
use HeyMoon\MemcachedLib\Exception\Exception;
use Throwable;

class AsyncClientTest extends AbstractClientTestCase
{
    protected function setUp(): void
    {
        $this->client = new AsyncClient();
    }

    /**
     * @throws Exception
     * @throws Throwable
     * @covers AsyncClient::getAsync
     * @covers AsyncClient::setAsync
     * @covers AsyncClient::deleteAsync
     * @covers AsyncClient::executeAsync
     * @covers AsyncClient::readAsync
     */
    public function testClientMethods()
    {
        $this->markTestSkipped();
        /** @var AsyncClient $client */
        $client = $this->client;
        $client->connect(Config::parseFromUrl(static::NET_URL));
        $done = false;
        $client->setAsync('key', 'value', 0, function () use (&$done) {
            $done = true;
        });
        while ($client->isRunning()) {
            sleep(1);
        }
        $this->assertTrue($done, 'Set not done');
        $result = null;
        $client->getAsync('key', function ($response) use (&$result) {
            $result = $response;
        });
        while ($client->isRunning()) {
            sleep(1);
        }
        $this->assertEquals('value', $result);
        $client->deleteAsync('key');
        while ($client->isRunning()) {
            sleep(1);
        }
        $client->getAsync('key', function ($response) use (&$result) {
            $result = $response;
        });
        while ($client->isRunning()) {
            sleep(1);
        }
        $this->assertNull($result);
        $this->expectException(DeleteException::class);
        $client->deleteAsync('key');
        while ($client->isRunning()) {
            sleep(1);
        }
    }
}
