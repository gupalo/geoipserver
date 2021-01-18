<?php

namespace App\Response;

use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;

class NotFoundResponseFactory implements ResponseFactoryInterface
{
    public function create(Request $request): Response
    {
        return new Response(404, ['Content-Type' => 'text/plain'], '404 Not Found');
    }
}
