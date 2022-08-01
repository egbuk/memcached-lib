<?php

namespace HeyMoon\MemcachedLib\Tests;

use HeyMoon\MemcachedLib\Client\Client;

class ClientTest extends AbstractClientTestCase
{
    protected function setUp(): void
    {
        $this->client = new Client();
    }
}
