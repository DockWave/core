<?php

namespace DockWave\Core\TransferMethod\queue;

use DockWave\Core\TransferMethod\ExecTransferInterface;
use DockWave\Core\TransferMethod\TransferMethodInterface;
use Psr\Log\LoggerInterface;

class TransferMethodQueue implements TransferMethodInterface
{

    public function get(): ExecTransferInterface
    {
        return new ExecTransferQueue($this->logger);
    }

    public function __construct(
        private readonly ?LoggerInterface $logger = null
    )
    {
    }
}