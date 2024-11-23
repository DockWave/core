<?php

namespace DockWave\Core;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

final class CoreKey
{
    private readonly \OpenSSLAsymmetricKey $privateKey;

    public function __construct(
        string $pathKey,
        #[\SensitiveParameter]
        string $passphrase
    )
    {
        if (!file_exists($pathKey)) {
            throw new \Exception('Don\'t have key');
        }
        $this->privateKey = openssl_pkey_get_private(
            file_get_contents($pathKey),
            $passphrase
        );
    }

    public function encode(array $data, int $ext = 3600): string
    {
        $payload = new CoreKeyPayload($data, time() + $ext);

        return JWT::encode($payload->toArray(), $this->privateKey, 'RS256');
    }

    public function decode(string $token): CoreKeyPayload
    {
        $publicKey = openssl_pkey_get_details($this->privateKey)['key'];

         return new CoreKeyPayload(...(array)JWT::decode($token, new Key($publicKey, 'RS256')));
    }
}