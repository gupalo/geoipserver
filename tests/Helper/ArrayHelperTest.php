<?php

namespace App\Tests\Helper;

use App\Helper\ArrayHelper;
use PHPUnit\Framework\TestCase;

class ArrayHelperTest extends TestCase
{
    public function testToUniqArray(): void
    {
        $arrayHelper = new ArrayHelper();

        self::assertSame(['1.1.1.1', '2.2.2.2'], $arrayHelper->toUniqArray('1.1.1.1, 2.2.2.2, 1.1.1.1'));
    }
}
