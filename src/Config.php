<?php

namespace HeyMoon\MemcachedLib;

use HeyMoon\MemcachedLib\Contracts\ConfigInterface;
use HeyMoon\MemcachedLib\Exception\ConfigException;

class Config implements ConfigInterface
{
    public function __construct(
        private string $host = '',
        private int $port = -1,
        private ?float $timeout = null
    ) {}

    /**
     * @throws ConfigException
     */
    public static function parseFromUrl(string $url, ?float $timeout = null): static
    {
        $data = parse_url($url);
        $config = new static();
        $config->timeout = $timeout;
        if (array_key_exists('path', $data)) {
            $scheme = $data['scheme'] ?? 'unix';
            $config->host = "$scheme://{$data['path']}";
        } elseif (array_key_exists('host', $data)) {
            $config->host = $data['host'];
            $config->port = $data['port'] ?? -1;
        } else {
            throw new ConfigException("Couldn't parse url {$url}");
        }
        return $config;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @return float|null
     */
    public function getTimeout(): ?float
    {
        return $this->timeout;
    }
}
