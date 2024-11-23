<?php

namespace DockWave\Core\TransferMethod\proxy;

use DockWave\Core\TransferMethod\ExecTransferInterface;
use DockWave\Core\TransferMethod\TransferMethodInterface;
use Psr\Log\LoggerInterface;

class TransferMethodProxy implements TransferMethodInterface
{

    public function get(): ExecTransferInterface
    {
        return new ExecTransferProxy($this->logger);
    }

    public function __construct(
        private readonly ?LoggerInterface $logger = null
    )
    {
    }
}