<?php

namespace App\Server;

use App\Response\ApiGeoIpResponseFactory;
use App\Response\FaviconResponseFactory;
use App\Response\HealthcheckResponseFactory;
use App\Response\MyIpJsonResponseFactory;
use App\Response\MyIpResponseFactory;
use App\Response\NotFoundResponseFactory;
use App\Response\RobotsTxtResponseFactory;
use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;

class Router
{
    public function __construct(
        private ?ApiGeoIpResponseFactory $apiGeoIpResponseFactory = null,
        private ?MyIpResponseFactory $myIpResponseFactory = null,
        private ?MyIpJsonResponseFactory $myIpJsonResponseFactory = null,
        private ?FaviconResponseFactory $faviconReponseFactory = null,
        private ?RobotsTxtResponseFactory $robotsTxtReponseFactory = null,
        private ?NotFoundResponseFactory $notFoundResponseFactory = null,
        private ?HealthcheckResponseFactory $healthcheckResponseFactory = null,
    ) {
        $this->apiGeoIpResponseFactory ??= new ApiGeoIpResponseFactory();
        $this->myIpResponseFactory ??= new MyIpResponseFactory();
        $this->myIpJsonResponseFactory ??= new MyIpJsonResponseFactory();
        $this->faviconReponseFactory ??= new FaviconResponseFactory();
        $this->robotsTxtReponseFactory ??= new RobotsTxtResponseFactory();
        $this->notFoundResponseFactory ??= new NotFoundResponseFactory();
        $this->healthcheckResponseFactory ??= new HealthcheckResponseFactory();
    }

    public function createResponse(Request $request): Response
    {
        $result = match ($request->path()) {
            '/api/geoip' => $this->apiGeoIpResponseFactory->create($request),
            '/myip' => $this->myIpResponseFactory->create($request),
            '/myip/json' => $this->myIpJsonResponseFactory->create($request),
            '/healthcheck' => $this->healthcheckResponseFactory->create($request),
            '/favicon.ico' => $this->faviconReponseFactory->create($request),
            '/robots.txt' => $this->robotsTxtReponseFactory->create($request),
            default => $this->notFoundResponseFactory->create($request),
        };

        $result->header('Access-Control-Allow-Origin', '*');

        return $result;
    }
}
