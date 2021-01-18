<?php

namespace App\Response\Helper;

use App\Helper\JsonHelper;
use Gupalo\GeoIp\GeoIpParser;
use Workerman\Protocols\Http\Response;

class GeoIpResponseHelper
{
    public function __construct(
        private ?GeoIpParser $geoIpParser = null,
        private ?JsonHelper $jsonHelper = null
    ) {
        $this->geoIpParser ??= new GeoIpParser(dirname(__DIR__, 3) . '/var/data/');
        $this->jsonHelper ??= new JsonHelper();
    }

    public function createResponse(array $ips, bool $isFull = false): Response
    {
        $result = [
            'ips' => [],
        ];
        foreach ($ips as $ip) {
            $geoip = $this->geoIpParser->parse($ip);
            $item = [
                'ip' => $ip,
                'country_code' => $geoip->getCountryCode(),
            ];
            if ($isFull) {
                $item['raw'] = $geoip->jsonSerialize();
            }

            $result['ips'][$ip] = $item;
        }

        return new Response(200, ['Content-Type' => 'application/json'], $this->jsonHelper->encode($result));
    }
}
