<?php

/** @noinspection PhpUndefinedMethodInspection */

namespace App\Tests\Response;

use App\Response\ApiGeoIpResponseFactory;
use App\Response\Helper\GeoIpResponseHelper;
use App\Security\Authenticator;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;

class ApiGeoIpResponseFactoryTest extends TestCase
{
    use ProphecyTrait;

    private Authenticator|ObjectProphecy $authenticator;
    private GeoIpResponseHelper|ObjectProphecy $geoIpResponseHelper;
    private ApiGeoIpResponseFactory $factory;

    protected function setUp(): void
    {
        $this->authenticator = $this->prophesize(Authenticator::class);
        $this->geoIpResponseHelper = $this->prophesize(GeoIpResponseHelper::class);

        $this->factory = new ApiGeoIpResponseFactory(
            $this->authenticator->reveal(),
            $this->geoIpResponseHelper->reveal(),
        );
    }

    public function testCreate(): void
    {
        $request = new Request(
            implode("\r\n", ['GET /api/geoip?ips=1.2.3.4,5.6.7.8 HTTP/1.1', 'X-Api-Key: key2']) .
            "\r\n\r\n" .
            implode("\n", [])
        );
        $response = new Response();

        $this->authenticator->isAuthenticated($request)->shouldBeCalledOnce()->willReturn(true);
        $this->geoIpResponseHelper->createResponse(['1.2.3.4', '5.6.7.8'], false)->shouldBeCalledOnce()->willReturn($response);

        self::assertSame($response, $this->factory->create($request));
    }

    public function testCreateFull(): void
    {
        $request = new Request(
            implode("\r\n", ['GET /api/geoip?ips=1.2.3.4,5.6.7.8&full=1 HTTP/1.1', 'X-Api-Key: key2']) .
            "\r\n\r\n" .
            implode("\n", [])
        );
        $response = new Response();

        $this->authenticator->isAuthenticated($request)->shouldBeCalledOnce()->willReturn(true);
        $this->geoIpResponseHelper->createResponse(['1.2.3.4', '5.6.7.8'], true)->shouldBeCalledOnce()->willReturn($response);

        self::assertSame($response, $this->factory->create($request));
    }

    public function testCreateNotAuthenticated(): void
    {
        $request = new Request(
            implode("\r\n", ['GET /api/geoip?ips=1.2.3.4,5.6.7.8 HTTP/1.1']) .
            "\r\n\r\n" .
            implode("\n", [])
        );

        $this->authenticator->isAuthenticated($request)->shouldBeCalledOnce()->willReturn(false);

        $response = $this->factory->create($request);

        self::assertSame(403, $response->getStatusCode());
        self::assertSame('application/json', $response->getHeader('Content-Type'));
        self::assertSame('{"error":"not_authorized"}', $response->rawBody());
    }
}
