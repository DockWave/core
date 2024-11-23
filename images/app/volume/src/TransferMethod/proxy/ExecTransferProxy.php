<?php

namespace DockWave\Core\TransferMethod\proxy;

use DockWave\Core\CoreRequest;
use DockWave\Core\TransferMethod\ExecTransferInterface;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use React\Http\Message\Response;
use React\Promise\Promise;
use React\Promise\PromiseInterface;

class ExecTransferProxy implements ExecTransferInterface
{

    public function go(string $name, string $to, CoreRequest $request): PromiseInterface
    {
        $logger = $this->logger;

        return new Promise(function ($resolve) use ($to, $request, $name, $logger) {
            $client = new Client();
            $queryString = new QueryString($request->getQueryParams());
            $requestHeaders = $request->getHeaders();
            unset($requestHeaders['Host']);

            $options = [
                'headers' => $requestHeaders,
                'body' => $request->getBody(),
                'http_errors' => false,
                'verify' => false,
            ];

            $path = str_replace($name . '/', '', $request->getUrl()['path']);
            $url = $to . $path . '?' . $queryString->toString();

            $response = $client->request(
                $request->getMethod(),
                $url,
                $options
            );

            $logger?->debug(implode(':', [$request->getUuid(), ExecTransferProxy::class, 'go']), [
                'request' => [
                    'method' => $request->getMethod(),
                    'url' => $url,
                    'options' => $options
                ],
                'response' => [
                    'status' => $response->getStatusCode(),
                    'headers' => $response->getHeaders(),
                    'body' =>[
                        'size' => $response->getBody()->getSize(),
                    ],
                ]
            ]);

            $resolve(new Response(
                status: $response->getStatusCode(),
                headers: $response->getHeaders(),
                body: $response->getBody()->getContents()
            ));
        });
    }

    public function __construct(
        private readonly ?LoggerInterface $logger = null
    )
    {
    }
}