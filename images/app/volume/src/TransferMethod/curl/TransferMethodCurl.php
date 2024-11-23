<?php

namespace DockWave\Core\TransferMethod\curl;

use DockWave\Core\TransferMethod\ExecTransferInterface;
use DockWave\Core\TransferMethod\TransferMethodInterface;
use Psr\Log\LoggerInterface;

class TransferMethodCurl implements TransferMethodInterface
{
    public function __construct(
        private readonly ?LoggerInterface $logger = null
    )
    {
    }

    public function get(): ExecTransferInterface
    {
        return new ExecTransferCurl($this->logger);
    }
}