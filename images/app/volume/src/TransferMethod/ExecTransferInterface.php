<?php

namespace DockWave\Core\TransferMethod;

use DockWave\Core\CoreRequest;
use Psr\Log\LoggerInterface;
use React\Promise\PromiseInterface;

interface ExecTransferInterface
{
    public function __construct(?LoggerInterface $logger = null);
    public function go(string $name, string $to, CoreRequest $request): PromiseInterface;
}
