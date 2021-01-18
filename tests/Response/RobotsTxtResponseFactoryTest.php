<?php

namespace App\Tests\Response;

use App\Response\RobotsTxtResponseFactory;
use PHPUnit\Framework\TestCase;
use Workerman\Protocols\Http\Request;

class RobotsTxtResponseFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $factory = new RobotsTxtResponseFactory();

        $response = $factory->create(new Request(''));

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('text/plain', $response->getHeader('Content-Type'));
        self::assertTrue(str_contains($response->rawBody(), 'Disallow: /'));
    }
}
