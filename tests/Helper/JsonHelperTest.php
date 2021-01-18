<?php

namespace App\Tests\Helper;

use App\Helper\JsonHelper;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class JsonHelperTest extends TestCase
{
    public function testEncode(): void
    {
        $jsonHelper = new JsonHelper();

        self::assertSame('{"key":"value"}', $jsonHelper->encode(['key' => 'value']));
    }

    public function testEncodeStrange(): void
    {
        $jsonHelper = new JsonHelper();

        self::assertSame('{}', $jsonHelper->encode(fn($a) => $a));
        self::assertSame('{}', $jsonHelper->encode(new RuntimeException('test')));
    }

    public function testEncodeUnsupported(): void
    {
        $jsonHelper = new JsonHelper();

        self::assertSame('{"error":"Type is not supported"}', $jsonHelper->encode(fopen(__FILE__, 'rb')));
    }
}
