<?php

namespace App\Server;

use App\Response\ApiGeoipResponseFactory;
use App\Response\MyIpJsonResponseFactory;
use App\Response\MyIpResponseFactory;
use App\Response\FaviconResponseFactory;
use App\Response\HealthcheckResponseFactory;
use App\Response\NotFoundResponseFactory;
use App\Response\RobotsTxtResponseFactory;
use Workerman\Connection\ConnectionInterface;
use Workerman\Protocols\Http\Request;
use Workerman\Worker;

class Server
{
    public static ?string $ip = null;

    public function __construct(
        private ?ApiGeoipResponseFactory $apiGeoipResponseFactory = null,
        private ?MyIpResponseFactory $myIpResponseFactory = null,
        private ?MyIpJsonResponseFactory $myIpJsonResponseFactory = null,
        private ?FaviconResponseFactory $faviconReponseFactory = null,
        private ?RobotsTxtResponseFactory $robotsTxtReponseFactory = null,
        private ?NotFoundResponseFactory $notFoundResponseFactory = null,
        private ?HealthcheckResponseFactory $healthcheckResponseFactory = null,
    ) {
        $this->apiGeoipResponseFactory ??= new ApiGeoipResponseFactory();
        $this->myIpResponseFactory ??= new MyIpResponseFactory();
        $this->myIpJsonResponseFactory ??= new MyIpJsonResponseFactory();
        $this->faviconReponseFactory ??= new FaviconResponseFactory();
        $this->robotsTxtReponseFactory ??= new RobotsTxtResponseFactory();
        $this->notFoundResponseFactory ??= new NotFoundResponseFactory();
        $this->healthcheckResponseFactory ??= new HealthcheckResponseFactory();
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
        self::$ip = $connection->getRemoteIp();

        $response = match ($request->path()) {
            '/api/geoip' => $this->apiGeoipResponseFactory->create($request),
            '/myip' => $this->myIpResponseFactory->create($request),
            '/myip/json' => $this->myIpJsonResponseFactory->create($request),
            '/healthcheck' => $this->healthcheckResponseFactory->create($request),
            '/favicon.ico' => $this->faviconReponseFactory->create($request),
            '/robots.txt' => $this->robotsTxtReponseFactory->create($request),
            default => $this->notFoundResponseFactory->create($request),
        };
        $connection->send($response);
    }
}



