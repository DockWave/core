<?php

namespace DockWave\Core\TransferMethod\curl;

use DockWave\Core\CoreKey;
use DockWave\Core\CoreRequest;
use DockWave\Core\TransferMethod\ExecTransferInterface;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use React\Http\Message\Response;
use React\Promise\Promise;
use React\Promise\PromiseInterface;

class ExecTransferCurl implements ExecTransferInterface
{
    public function go(string $name, string $to, CoreRequest $request): PromiseInterface
    {
        $logger = $this->logger;

        return new Promise(function ($resolve) use ($to, $request, $logger) {
            $client = new Client();
            $response = $client->post($to, [
                'body' => $request->sign()
            ]);

            $logger?->debug(implode(':', [$request->getUuid(), ExecTransferCurl::class, 'go']), [
                'request' => [
                    'to' => $to,
                    'body' => $request->sign()
                ]
            ]);

            $resolve(new Response(body: $response->getBody()->getContents()));
        });
    }

    public function __construct(
        private readonly ?LoggerInterface $logger = null
    )
    {
    }
}