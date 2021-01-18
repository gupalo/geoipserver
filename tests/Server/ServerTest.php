<?php

namespace App\Tests\Server;

use App\Server\Server;
use PHPUnit\Framework\TestCase;

class ServerTest extends TestCase
{
    public function testConstruct(): void
    {
        $server = new Server();

        self::assertNotNull($server);
    }
}
