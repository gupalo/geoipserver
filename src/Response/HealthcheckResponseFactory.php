<?php

namespace App\Response;

use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;

class HealthcheckResponseFactory implements ResponseFactoryInterface
{
    public function create(Request $request): Response
    {
        return new Response(200, ['Content-Type' => 'text/plain'], 'ok');
    }
}
