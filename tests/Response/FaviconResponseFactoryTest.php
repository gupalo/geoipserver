<?php

namespace App\Tests\Response;

use App\Response\FaviconResponseFactory;
use PHPUnit\Framework\TestCase;
use Workerman\Protocols\Http\Request;

class FaviconResponseFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $factory = new FaviconResponseFactory();

        $response = $factory->create(new Request(''));

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('image/x-icon', $response->getHeader('Content-Type'));
        self::assertLessThan(1000, strlen($response->rawBody()));
    }
}
