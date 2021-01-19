<?php

/** @noinspection PhpUndefinedMethodInspection */

namespace App\Tests\Response;

use App\Request\RealIpExtractor;
use App\Response\MyIpResponseFactory;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Workerman\Protocols\Http\Request;

class MyIpResponseFactoryTest extends TestCase
{
    use ProphecyTrait;

    private RealIpExtractor|ObjectProphecy $realIpExtractor;

    protected function setUp(): void
    {
        $this->realIpExtractor = $this->prophesize(RealIpExtractor::class);
    }

    public function testCreate(): void
    {
        $request = new Request('');

        $this->realIpExtractor->getRealIp($request)->shouldBeCalledOnce()->willReturn('1.2.3.4');

        $response = (new MyIpResponseFactory($this->realIpExtractor->reveal()))->create($request);

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('text/plain', $response->getHeader('Content-Type'));
        self::assertSame('1.2.3.4', $response->rawBody());
    }
}
