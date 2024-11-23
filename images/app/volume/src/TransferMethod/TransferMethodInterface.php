<?php

namespace DockWave\Core\TransferMethod;

use Psr\Log\LoggerInterface;

interface TransferMethodInterface
{
    public function __construct(?LoggerInterface $logger = null);
    public function get(): ExecTransferInterface;
}
