<?php

namespace DockWave\Core;

use Psr\Http\Message\ServerRequestInterface;

class CoreRequest
{
    private readonly array $headers;
    private readonly string $body;
    private readonly string $method;
    private readonly array $url;
    private readonly array $attributes;
    private readonly array $params;
    private readonly array $cookies;
    private readonly array $queryParams;
    private ?string $sign = null;

    public function __construct(
        ServerRequestInterface $request,
        #[\SensitiveParameter]
        private readonly CoreKey $key,
        private readonly string $uuid
    )
    {
        $this->headers = $request->getHeaders();
        $this->body = $request->getBody()->getContents();
        $this->method = $request->getMethod();
        $this->attributes = $request->getAttributes();
        $this->params = $request->getQueryParams();
        $this->cookies = $request->getCookieParams();
        $this->queryParams = $request->getQueryParams();
        $this->url = [
            'host' => $request->getUri()->getHost(),
            'path' => $request->getUri()->getPath(),
            'authority' => $request->getUri()->getAuthority(),
            'port' => $request->getUri()->getPort(),
            'query' => $request->getUri()->getQuery(),
            'userInfo' => $request->getUri()->getUserInfo(),
            'scheme' => $request->getUri()->getScheme(),
        ];
    }

    public function toArray(): array
    {
        return [
            'request_uuid' => $this->uuid,
            'body' => $this->body,
            'headers' => $this->headers,
            'url' => $this->url,
            'method' => $this->method,
            'attributes' => $this->attributes,
            'params' => $this->params,
            'cookies' => $this->cookies,
            'queryParams' => $this->queryParams,
        ];
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUrl(): array
    {
        return $this->url;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getCookies(): array
    {
        return $this->cookies;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function sign(int $extTime = 3600): string
    {
        if (!$this->sign) {
            $this->sign = $this->key->encode($this->toArray(), $extTime);
        }
        return $this->sign;
    }
}