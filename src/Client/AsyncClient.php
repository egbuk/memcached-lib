<?php

namespace HeyMoon\MemcachedLib\Client;

use Fiber;
use HeyMoon\MemcachedLib\Contracts\AsyncCacheInterface;
use HeyMoon\MemcachedLib\Contracts\ConfigInterface;
use HeyMoon\MemcachedLib\Exception\AlreadyExecutingException;
use HeyMoon\MemcachedLib\Exception\ConnectException;
use HeyMoon\MemcachedLib\Exception\DeleteException;
use HeyMoon\MemcachedLib\Exception\InvalidKeyException;
use HeyMoon\MemcachedLib\Exception\WriteException;
use Throwable;

class AsyncClient extends AbstractClient implements AsyncCacheInterface
{
    private ?Fiber $fiber = null;

    public function isRunning(): bool
    {
        return $this->fiber?->isRunning() === true;
    }

    /**
     * @throws Throwable
     * @throws InvalidKeyException
     * @throws AlreadyExecutingException
     */
    public function getAsync(string $key, callable $callback): void
    {
        $this->executeAsync(sprintf("get %s\r\n", $this->checkKey($key)), function ($response) use ($callback) {
            preg_match('/VALUE \w+ \d+ (\d+)/m', $response, $matches);
            if (array_key_exists(1, $matches)) {
                $this->readAsync($matches[1]+1, $callback);
            } else {
                $callback(null);
            }
        });
    }

    /**
     * @throws Throwable
     * @throws InvalidKeyException
     * @throws AlreadyExecutingException
     */
    public function setAsync(string $key, string $value, int $ttl = 0, ?callable $callback = null): void
    {
        $length = mb_strlen($value, '8bit');
        $this->executeAsync(sprintf("set %s 0 %d %d\r\n%s\r\n", $this->checkKey($key), $ttl, $length, $value), function ($response) use ($callback) {
            if ($response !== "STORED\r\n") {
                throw new WriteException($response);
            }
            $callback();
        });
    }

    /**
     * @throws Throwable
     * @throws InvalidKeyException
     * @throws AlreadyExecutingException
     */
    public function deleteAsync(string $key, ?callable $callback = null): void
    {
        $this->executeAsync(sprintf("delete %s\r\n", $this->checkKey($key)), function ($response) use ($callback) {
            if ($response !== "\r\n") {
                throw new DeleteException($response);
            }
            $callback();
        });
    }

    /**
     * @throws AlreadyExecutingException
     * @throws Throwable
     */
    protected function readAsync(?int $length = null, ?callable $callback = null)
    {
        $this->checkExecuting();
        $this->fiber = new Fiber(function (?int $length, ?callable $callback) {
            $response = $this->read($length);
            $currentLength = $response ? mb_strlen($response, '8bit') : 0;
            while ((!$length && !$response) || ($length && (!$response || $currentLength < $length))) {
                $response .= $this->read($length - $currentLength);
            }
            $callback($response);
        });
        $this->fiber->start($length, $callback);
    }

    /**
     * @throws AlreadyExecutingException
     * @throws Throwable
     */
    protected function executeAsync(string $command, ?callable $callback): void
    {
        $this->checkExecuting();
        $this->fiber = new Fiber(function (string $command, ?callable $callback) {
            $response = $this->execute($command);
            while (!$response) {
                $response = $this->read();
            }
            $this->fiber = null;
            $callback($response);
        });
        $this->fiber->start($command, $callback);
    }

    /**
     * @throws AlreadyExecutingException
     */
    protected function checkExecuting(): void
    {
        if ($this->fiber?->isRunning()) {
            throw new AlreadyExecutingException();
        };
    }

    public function connect(ConfigInterface $config): void
    {
        parent::connect($config);
        if (!stream_set_blocking($this->socket, false)) {
            throw new ConnectException('Failed to switch to non-blocking mode');
        }
    }
}
