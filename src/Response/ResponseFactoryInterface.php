<?php

namespace App\Response;

use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;

interface ResponseFactoryInterface
{
    public function create(Request $request): Response;
}
