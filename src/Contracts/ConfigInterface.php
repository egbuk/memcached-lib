<?php

namespace HeyMoon\MemcachedLib\Contracts;

interface ConfigInterface
{
    public function getHost(): string;

    public function getPort(): ?int;

    public function getTimeout(): ?float;
}
