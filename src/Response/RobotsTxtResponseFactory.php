<?php

namespace App\Response;

use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;

class RobotsTxtResponseFactory implements ResponseFactoryInterface
{
    public function create(Request $request): Response
    {
        return new Response(
            200,
            ['Content-Type' => 'text/plain'],
            implode("\n", ['User-agent: *', 'Disallow: /', ''])
        );
    }
}
