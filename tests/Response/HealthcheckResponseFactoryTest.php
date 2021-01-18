<?php

namespace App\Tests\Response;

use App\Response\HealthcheckResponseFactory;
use PHPUnit\Framework\TestCase;
use Workerman\Protocols\Http\Request;

class HealthcheckResponseFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $factory = new HealthcheckResponseFactory();

        $response = $factory->create(new Request(''));

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('text/plain', $response->getHeader('Content-Type'));
        self::assertSame('ok', $response->rawBody());
    }
}
