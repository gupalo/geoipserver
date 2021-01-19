<?php

namespace App\Tests\Request;

use App\Request\RealIpExtractor;
use PHPUnit\Framework\TestCase;
use Workerman\Protocols\Http\Request;

class RealIpExtractorTest extends TestCase
{
    public function testGetRealIpCfConnectingIp(): void
    {
        $request = new Request(
            implode("\r\n", ['GET /test HTTP/1.1', 'CF-Connecting-IP: 1.2.3.4']) .
            "\r\n\r\n" .
            implode("\n", [])
        );

        $realIpExtractor = new RealIpExtractor();

        self::assertSame('1.2.3.4', $realIpExtractor->getRealIp($request));
    }

    public function testGetRealIpXRealIp(): void
    {
        $request = new Request(
            implode("\r\n", ['GET /test HTTP/1.1', 'X-Real-IP: 1.2.3.4']) .
            "\r\n\r\n" .
            implode("\n", [])
        );

        $realIpExtractor = new RealIpExtractor();

        self::assertSame('1.2.3.4', $realIpExtractor->getRealIp($request));
    }

    public function testGetRealIpXForwardedFor(): void
    {
        $request = new Request(
            implode("\r\n", ['GET /test HTTP/1.1', 'X-Forwarded-For: 1.2.3.4']) .
            "\r\n\r\n" .
            implode("\n", [])
        );

        $realIpExtractor = new RealIpExtractor();

        self::assertSame('1.2.3.4', $realIpExtractor->getRealIp($request));
    }

    public function testGetRealIpPriority(): void
    {
        $request = new Request(
            implode("\r\n", ['GET /test HTTP/1.1', 'X-Real-IP: 2.2.2.2', 'X-Forwarded-For: 3.3.3.3', 'CF-Connecting-IP: 1.1.1.1']) .
            "\r\n\r\n" .
            implode("\n", [])
        );

        $realIpExtractor = new RealIpExtractor();

        self::assertSame('1.1.1.1', $realIpExtractor->getRealIp($request));
    }

    public function testGetRealIpPriority2(): void
    {
        $request = new Request(
            implode("\r\n", ['GET /test HTTP/1.1', 'X-Real-IP: 2.2.2.2', 'X-Forwarded-For: 3.3.3.3']) .
            "\r\n\r\n" .
            implode("\n", [])
        );

        $realIpExtractor = new RealIpExtractor();

        self::assertSame('2.2.2.2', $realIpExtractor->getRealIp($request));
    }

    public function testGetRealIpSeveral(): void
    {
        $request = new Request(
            implode("\r\n", ['GET /test HTTP/1.1', 'X-Real-IP: 1.1.1.1, 2.2.2.2']) .
            "\r\n\r\n" .
            implode("\n", [])
        );

        $realIpExtractor = new RealIpExtractor();

        self::assertSame('1.1.1.1', $realIpExtractor->getRealIp($request));
    }

    public function testGetRealIps(): void
    {
        $request = new Request(
            implode("\r\n", ['GET /test HTTP/1.1', 'X-Real-IP: 1.1.1.1, 2.2.2.2']) .
            "\r\n\r\n" .
            implode("\n", [])
        );

        $realIpExtractor = new RealIpExtractor();

        self::assertSame(['1.1.1.1', '2.2.2.2'], $realIpExtractor->getRealIps($request));
    }

    public function testGetRealIpDefault(): void
    {
        $request = new Request(
            implode("\r\n", ['GET /test HTTP/1.1']) .
            "\r\n\r\n" .
            implode("\n", [])
        );

        $realIpExtractor = new RealIpExtractor();

        self::assertSame('unknown', $realIpExtractor->getRealIp($request));
    }

    public function testGetRealIpDefaultOverride(): void
    {
        $request = new Request(
            implode("\r\n", ['GET /test HTTP/1.1']) .
            "\r\n\r\n" .
            implode("\n", [])
        );

        $realIpExtractor = new RealIpExtractor();
        RealIpExtractor::$defaultIp = '5.5.5.5';

        self::assertSame('5.5.5.5', $realIpExtractor->getRealIp($request));
    }
}
