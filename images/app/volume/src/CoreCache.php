<?php

namespace DockWave\Core;

use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use React\Cache\CacheInterface;

use function React\Async\await;

class CoreCache
{
    private ?string $name = null;
    private bool $isAllowCache = false;
    private ?float $ttl = null;

    public function __construct(
        private readonly CacheInterface $cache,
        private readonly string $uuid,
        private readonly ?LoggerInterface $logger,
    )
    {
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setIsAllowCache(bool $isAllowCache): self
    {
        $this->isAllowCache = $isAllowCache;
        return $this;
    }

    public function setTtl(?float $ttl): self
    {
        $this->ttl = $ttl;
        return $this;
    }

    private function getCacheKey(CoreRequest $req): string
    {
        $path = [
            $req->getMethod(),
            $req->getUrl(),
            $req->getHeaders(),
            $req->getQueryParams(),
            $req->getCookies(),
            $req->getParams()
        ];

        $key = '/' . $this->name . '/' . sha1(json_encode($path));
        $this->logger?->debug(implode(':', [$this->uuid, __CLASS__, __FUNCTION__]), ['key' => $key, 'req' => $req->toArray()]);
        return $key;
    }

    public function getCache(CoreRequest $req): ?ResponseInterface
    {
        $key = $this->getCacheKey($req);

        $has = await($this->cache->has($key));
        if (!$has) {
            $this->logger?->debug(implode(':', [$this->uuid, __CLASS__, __FUNCTION__]), ['status' => false]);
            return null;
        }

        $this->logger?->debug(implode(':', [$this->uuid, __CLASS__, __FUNCTION__]), ['status' => true]);
        return unserialize(await($this->cache->get($key)));
    }

    public function setCache(CoreRequest $req, ResponseInterface $res): ResponseInterface
    {
        if ($this->isAllowCache) {
            $key = $this->getCacheKey($req);

            $this->cache->set(
                key: $key,
                value: serialize($res),
                ttl: $this->ttl
            );
            $this->logger?->debug(implode(':', [$this->uuid, __CLASS__, __FUNCTION__]), [
                'key' => $key,
                'ttl' => $this->ttl,
                'value' => $res
            ]);
        }

        return $res;
    }
}