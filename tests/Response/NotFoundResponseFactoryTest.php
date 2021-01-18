<?php

namespace App\Tests\Response;

use App\Response\NotFoundResponseFactory;
use PHPUnit\Framework\TestCase;
use Workerman\Protocols\Http\Request;

class NotFoundResponseFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $factory = new NotFoundResponseFactory();

        $response = $factory->create(new Request(''));

        self::assertSame(404, $response->getStatusCode());
        self::assertSame('text/plain', $response->getHeader('Content-Type'));
        self::assertTrue(str_contains($response->rawBody(), '404'));
    }
}
