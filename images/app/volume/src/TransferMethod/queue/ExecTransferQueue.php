<?php

namespace DockWave\Core\TransferMethod\queue;

use DockWave\Core\CoreRequest;
use DockWave\Core\TransferMethod\ExecTransferInterface;
use Psr\Log\LoggerInterface;
use React\Promise\Promise;
use React\Promise\PromiseInterface;

use function React\Promise\reject;
use function React\Promise\resolve;

class ExecTransferQueue implements ExecTransferInterface
{
    public function go(string $name, string $to, CoreRequest $request): PromiseInterface
    {
        $this->logger?->error(implode(':', [$request->getUuid(), __CLASS__, __FUNCTION__]));
        return new Promise(function () {
            reject(new \Exception('not implemented'));
        });
    }

    public function __construct(
        private readonly ?LoggerInterface $logger = null
    )
    {
    }
}