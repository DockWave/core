<?php

namespace DockWave\Core;

use DockWave\Core\TransferMethod\TransferMethodFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use React\Cache\CacheInterface;

use React\Promise\PromiseInterface;

use function React\Async\await;

class Transfer
{
    private ?array $config = null;
    private ?string $name = null;
    private bool $isAllowCache = false;
    private readonly string $uuid;
    private readonly ?CoreCache $cache;

    public function __construct(
        private readonly ServerRequestInterface $request,
        ?CacheInterface $cache = null,
        private readonly ?LoggerInterface $logger = null,
    ) {
        $this->uuid = Uuid::uuid4()->toString();

        $this->cache = ($cache)
            ? new CoreCache(
                $cache,
                $this->uuid,
                $this->logger
            )
            : null;
    }

    public function validate(string $name, array $config): bool
    {
        $status = str_starts_with($this->request->getUri()->getPath(), $config['uri']);
        if ($status) {
            $this->config = $config;
            $this->name = $name;
            $this->isAllowCache = $this->config['cache']['enable'] ?? null === 'true';
            $this->cache
                ->setName($name)
                ->setIsAllowCache($this->isAllowCache)
                ->setTtl($config['cache']['ttl'] ?? null);
        }
        $this->logger?->debug(implode(':', [$this->uuid, __CLASS__, __FUNCTION__]), ['status' => $status]);
        return $status;
    }

    public function go(
        #[\SensitiveParameter]
        CoreKey $key
    ): ResponseInterface {
        $req = new CoreRequest($this->request, $key, $this->uuid);

        if ($this->isAllowCache) {
            $cached = $this->cache->getCache($req);
            if ($cached) {
                return $cached;
            }
        }

        $transfer = TransferMethodFactory::create($this->config['method']);
        $this->logger?->debug(implode(':', [$this->uuid, __CLASS__, __FUNCTION__, 'transfer']), [
            'transfer' => $transfer,
            'name' => $this->name,
            'to' => $this->config['to'],
            'req' => $req
        ]);
        $res = await($transfer->get()->go($this->name, $this->config['to'], $req));

        return $this->cache->setCache($req, $res);
    }
}
