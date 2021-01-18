<?php

namespace App\Tests\Security;

use App\Security\Authenticator;
use PHPUnit\Framework\TestCase;
use Workerman\Protocols\Http\Request;

class AuthenticatorTest extends TestCase
{
    // array $keys, string $method, string $uri, array $headers, string $body

    public function testIsAuthenticated(): void
    {
        $authenticator = new Authenticator(['key1', 'key2']);

        $request = new Request(
            implode("\r\n", ['GET /test?apikey=key1 HTTP/1.1']) .
            "\r\n\r\n" .
            implode("\n", [])
        );

        self::assertTrue($authenticator->isAuthenticated($request));
    }

    public function testIsAuthenticatedBecauseNoKeys(): void
    {
        $authenticator = new Authenticator();

        $request = new Request(
            implode("\r\n", ['GET /test HTTP/1.1']) .
            "\r\n\r\n" .
            implode("\n", [])
        );

        self::assertTrue($authenticator->isAuthenticated($request));
    }

    public function testIsAuthenticatedHeader(): void
    {
        $authenticator = new Authenticator(['key1', 'key2']);

        $request = new Request(
            implode("\r\n", ['GET /test?apikey=key1 HTTP/1.1', 'X-Api-Key: key2']) .
            "\r\n\r\n" .
            implode("\n", [])
        );

        self::assertTrue($authenticator->isAuthenticated($request));
    }

    public function testIsAuthenticatedBody(): void
    {
        $authenticator = new Authenticator(['key1', 'key2']);

        $request = new Request(
            implode("\r\n", ['POST /test?apikey=key1 HTTP/1.1']) .
            "\r\n\r\n" .
            implode("\n", ['test=test&apikey=key1'])
        );

        self::assertTrue($authenticator->isAuthenticated($request));
    }

    public function testIsAuthenticatedInvalidKey(): void
    {
        $authenticator = new Authenticator(['key1', 'key2']);

        $request = new Request(
            implode("\r\n", ['GET /test?apikey=key3']) .
            "\r\n\r\n" .
            implode("\n", [])
        );

        self::assertFalse($authenticator->isAuthenticated($request));
    }

    public function testIsAuthenticatedNoKey(): void
    {
        $authenticator = new Authenticator(['key1', 'key2']);

        $request = new Request(
            implode("\r\n", ['GET /test']) .
            "\r\n\r\n" .
            implode("\n", [])
        );

        self::assertFalse($authenticator->isAuthenticated($request));
    }
}
