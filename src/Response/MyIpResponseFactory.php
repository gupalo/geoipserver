<?php

namespace App\Response;

use App\Helper\ArrayHelper;
use App\Response\Helper\GeoIpResponseHelper;
use App\Server\Server;
use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;

class MyIpResponseFactory implements ResponseFactoryInterface
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
        $ips = $this->arrayHelper->toUniqArray($request->header('CF-Connecting-IP', $request->header('X-Real-IP', $request->header('X-Forwarded-For', Server::$ip))));
        $ip = $ips[0] ?? 'unknown';

        return new Response(200, ['Content-Type' => 'text/plain'], $ip);
    }
}
