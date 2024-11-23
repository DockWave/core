<?php

namespace DockWave\Core;

class CoreKeyPayload
{
    public function __construct(
        private array $payload,
        private int $ext,
        private string $iss = 'core',
        private ?int $iat = null,
    )
    {
        if (!$this->iat) $this->iat = time();
    }

    public function getIss(): string
    {
        return $this->iss;
    }

    public function setIss(string $iss): void
    {
        $this->iss = $iss;
    }

    public function getIat(): int
    {
        return $this->iat;
    }

    public function setIat(int $iat): void
    {
        $this->iat = $iat;
    }

    public function getExt(): int
    {
        return $this->ext;
    }

    public function setExt(int $ext): void
    {
        $this->ext = $ext;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function setPayload(array $payload): void
    {
        $this->payload = $payload;
    }

    public function toArray(): array
    {
        return [
            'iss' => $this->getIss(),
            'iat' => $this->getIat(),
            'ext' => $this->getExt(),
            'payload' => $this->getPayload()
        ];
    }
}