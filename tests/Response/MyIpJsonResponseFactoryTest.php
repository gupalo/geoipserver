<?php

/** @noinspection PhpUndefinedMethodInspection */

namespace App\Tests\Response;

use App\Request\RealIpExtractor;
use App\Response\Helper\GeoIpResponseHelper;
use App\Response\MyIpJsonResponseFactory;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;

class MyIpJsonResponseFactoryTest extends TestCase
{
    use ProphecyTrait;

    private RealIpExtractor|ObjectProphecy $realIpExtractor;
    private GeoIpResponseHelper|ObjectProphecy $geoIpResponseHelper;

    protected function setUp(): void
    {
        $this->realIpExtractor = $this->prophesize(RealIpExtractor::class);
        $this->geoIpResponseHelper = $this->prophesize(GeoIpResponseHelper::class);
    }

    public function testCreate(): void
    {
        $request = new Request('');
        $response = new Response();

        $this->realIpExtractor->getRealIps($request)->shouldBeCalledOnce()->willReturn(['1.2.3.4', '5.6.7.8']);
        $this->geoIpResponseHelper->createResponse(['1.2.3.4'], false)->shouldBeCalledOnce()->willReturn($response);

        $factory = new MyIpJsonResponseFactory(
            $this->geoIpResponseHelper->reveal(),
            $this->realIpExtractor->reveal(),
        );

        self::assertSame($response, $factory->create($request));
    }
}
