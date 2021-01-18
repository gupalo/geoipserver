<?php

namespace App\Response;

use App\Helper\ArrayHelper;
use App\Response\Helper\GeoIpResponseHelper;
use App\Server\Server;
use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;

class MyIpJsonResponseFactory implements ResponseFactoryInterface
{
    public function __construct(
        private ?GeoIpResponseHelper $geoipResponseHelper = null,
        private ?ArrayHelper $arrayHelper = null,
    ) {
        $this->geoipResponseHelper ??= new GeoIpResponseHelper();
        $this->arrayHelper ??= new ArrayHelper();
    }

    public function create(Request $request): Response
    {
        $isFull = (bool)$request->get('full', false);

        $ips = $this->arrayHelper->toUniqArray($request->header('CF-Connecting-IP', $request->header('X-Real-IP', $request->header('X-Forwarded-For', Server::$ip))));
        $ips = array_slice($ips, 0, 1);

        return $this->geoipResponseHelper->createResponse($ips, $isFull);
    }
}
