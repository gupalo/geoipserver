<?php

namespace App\Response;

use App\Request\RealIpExtractor;
use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;

class MyIpResponseFactory implements ResponseFactoryInterface
{
    public function __construct(
        private ?RealIpExtractor $realIpExtractor = null,
    ) {
        $this->realIpExtractor ??= new RealIpExtractor();
    }

    public function create(Request $request): Response
    {
        return new Response(200, ['Content-Type' => 'text/plain'], $this->realIpExtractor->getRealIp($request));
    }
}
