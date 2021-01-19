<?php

namespace App\Response;

use App\Request\RealIpExtractor;
use App\Response\Helper\GeoIpResponseHelper;
use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;

class MyIpJsonResponseFactory implements ResponseFactoryInterface
{
    public function __construct(
        private ?GeoIpResponseHelper $geoIpResponseHelper = null,
        private ?RealIpExtractor $realIpExtractor = null,
    ) {
        $this->geoIpResponseHelper ??= new GeoIpResponseHelper();
        $this->realIpExtractor ??= new RealIpExtractor();
    }

    public function create(Request $request): Response
    {
        $isFull = (bool)$request->get('full', false);

        $ips = array_slice($this->realIpExtractor->getRealIps($request), 0, 1);

        return $this->geoIpResponseHelper->createResponse($ips, $isFull);
    }
}
