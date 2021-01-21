<?php

/** @noinspection PhpUndefinedMethodInspection */

namespace App\Tests\Server;

use App\Response\ApiGeoIpResponseFactory;
use App\Response\NotFoundResponseFactory;
use App\Server\Router;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;

class RouterTest extends TestCase
{
    use ProphecyTrait;

    private ApiGeoIpResponseFactory|ObjectProphecy $apiGeoipResponseFactory;
    private NotFoundResponseFactory|ObjectProphecy $notFoundResponseFactory;

    protected function setUp(): void
    {
        $this->apiGeoipResponseFactory = $this->prophesize(ApiGeoIpResponseFactory::class);
        $this->notFoundResponseFactory = $this->prophesize(NotFoundResponseFactory::class);
    }

    public function testApi(): void
    {
        $request = new Request(
            implode("\r\n", ['GET /api/geoip?ips=1.2.3.4,5.6.7.8 HTTP/1.1']) .
            "\r\n\r\n" .
            implode("\n", [])
        );
        $response = new Response();

        $this->apiGeoipResponseFactory->create($request)->shouldBeCalledOnce()->willReturn($response);

        $router = new Router($this->apiGeoipResponseFactory->reveal());

        self::assertSame($response, $router->createResponse($request));
        self::assertSame('*', $response->getHeader('Access-Control-Allow-Origin'));
    }

    public function testNotFound(): void
    {
        $request = new Request(
            implode("\r\n", ['GET /no-such-url HTTP/1.1']) .
            "\r\n\r\n" .
            implode("\n", [])
        );
        $response = new Response();

        $this->notFoundResponseFactory->create($request)->shouldBeCalledOnce()->willReturn($response);

        $router = new Router(notFoundResponseFactory: $this->notFoundResponseFactory->reveal());

        self::assertSame($response, $router->createResponse($request));
    }
}
