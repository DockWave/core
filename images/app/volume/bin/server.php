#!/usr/local/bin/php
<?php

use DockWave\Core\CoreKey;
use DockWave\Core\Transfer;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use React\Socket\SocketServer;
use React\Http\HttpServer;
use Symfony\Component\Yaml\Yaml;

require __DIR__ . '/../vendor/autoload.php';

$config = Yaml::parseFile('/conf.d/global.yaml');
$localCache = new React\Cache\ArrayCache($config['server']['cache-limit-items'] ?? null);
$key = new CoreKey(__DIR__ . '/../cert/key.pem', $_ENV['KEY_PASSPHRASE']);

$logger = null;
if ($config['server']['logger'] === 1) {
    $logger = new Logger('app');
    $logger->pushHandler(new StreamHandler(__DIR__ . '/../log/app-' . date('Y-m-d') . '.log'));
    $logger?->info('Logger initialized');
}

$http = new HttpServer(function (ServerRequestInterface $request) use ($logger, $localCache, $config, $key) {
    try {
        $transfer = new Transfer($request, $localCache, $logger);
        foreach ($config['routing'] as $name => $item) {
            if ($transfer->validate($name, $item)) {
                return $transfer->go($key);
            }
        }
        return Response::json(['error' => 404]);
    } catch (\Throwable $e) {
        $logger?->error('Server Error', ['exception' => $e]);
        return Response::json(['error' => 'Internal Server Error']);
    }
});

$socket = new SocketServer('0.0.0.0:' . $config['server']['port']);
$http->listen($socket);

$logger?->info('Server running at http://0.0.0.0:' . $config['server']['port']);