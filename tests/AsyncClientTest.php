<?php

namespace HeyMoon\MemcachedLib\Tests;

use HeyMoon\MemcachedLib\Client\AsyncClient;

class AsyncClientTest extends AbstractClientTestCase
{
    protected function setUp(): void
    {
        $this->client = new AsyncClient();
    }
}
