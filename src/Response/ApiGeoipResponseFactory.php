<?php

namespace App\Response;

use App\Helper\ArrayHelper;
use App\Helper\JsonHelper;
use App\Response\Helper\GeoIpResponseHelper;
use App\Security\Authenticator;
use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;

class ApiGeoipResponseFactory implements ResponseFactoryInterface
{
    public function __construct(
        private ?Authenticator $authenticator = null,
        private ?GeoIpResponseHelper $geoipResponseHelper = null,
        private ?ArrayHelper $arrayHelper = null,
        private ?JsonHelper $jsonHelper = null,
    ) {
        $this->authenticator ??= new Authenticator();
        $this->geoipResponseHelper ??= new GeoIpResponseHelper();
        $this->arrayHelper ??= new ArrayHelper();
        $this->jsonHelper ??= new JsonHelper();
    }

    public function create(Request $request): Response
    {
        if (!$this->authenticator->isAuthenticated($request)) {
            return new Response(403, ['Content-Type' => 'application/json'], $this->jsonHelper->encode(['error' => 'not_authorized']));
        }

        $isFull = (bool)$request->get('full', false);
        $ips = $this->arrayHelper->toUniqArray($request->get('ips', $request->post('ips', '')));

        return $this->geoipResponseHelper->createResponse($ips, $isFull);
    }
}
