<?php

namespace DockWave\Core\TransferMethod;

use DockWave\Core\TransferMethod\curl\TransferMethodCurl;
use DockWave\Core\TransferMethod\proxy\TransferMethodProxy;
use DockWave\Core\TransferMethod\queue\TransferMethodQueue;
use Psr\Log\LoggerInterface;

class TransferMethodFactory
{
    private const TRANSFER_METHOD = [
        'queue' => TransferMethodQueue::class,
        'curl' => TransferMethodCurl::class,
        'proxy' => TransferMethodProxy::class
    ];

    public static function create(string $type, ?LoggerInterface $logger = null): TransferMethodInterface
    {
        if (array_key_exists($type, self::TRANSFER_METHOD)) {
            $classname = self::TRANSFER_METHOD[$type];
            return new $classname($logger);
        }
        throw new \InvalidArgumentException("Invalid transfer method: {$type}");
    }
}