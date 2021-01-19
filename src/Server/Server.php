<?php

namespace App\Server;

use App\Request\RealIpExtractor;
use Workerman\Connection\ConnectionInterface;
use Workerman\Protocols\Http\Request;
use Workerman\Worker;

class Server
{
    public function __construct(private ?Router $router = null)
    {
        $this->router ??= new Router();
    }

    public function run(): void
    {
        $worker = new Worker($_ENV['LISTEN'] ?: 'http://0.0.0.0:80');
        $worker->count = $_ENV['WORKERS'] ?: 4;
        $worker->onMessage = fn(ConnectionInterface $connection, Request $request) => $this->onMessage($connection, $request);
        Worker::runAll();
    }

    private function onMessage(ConnectionInterface $connection, Request $request): void
    {
        RealIpExtractor::$defaultIp = $connection->getRemoteIp();

        $connection->send($this->router->createResponse($request));
    }
}



