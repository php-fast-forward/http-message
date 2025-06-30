<?php

declare(strict_types=1);

namespace Tests\FastForward\Http\Message;

use FastForward\Http\Message\RequestMethod;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(RequestMethod::class)]
final class RequestMethodTest extends TestCase
{
    #[DataProvider('provideSafeMethods')]
    public function testIsSafeWillReturnTrue(RequestMethod $method): void
    {
        $this->assertTrue($method->isSafe());
    }

    #[DataProvider('provideUnsafeMethods')]
    public function testIsSafeWillReturnFalse(RequestMethod $method): void
    {
        $this->assertFalse($method->isSafe());
    }

    #[DataProvider('provideIdempotentMethods')]
    public function testIsIdempotentWillReturnTrue(RequestMethod $method): void
    {
        $this->assertTrue($method->isIdempotent());
    }

    #[DataProvider('provideNonIdempotentMethods')]
    public function testIsIdempotentWillReturnFalse(RequestMethod $method): void
    {
        $this->assertFalse($method->isIdempotent());
    }

    #[DataProvider('provideCacheableMethods')]
    public function testIsCacheableWillReturnTrue(RequestMethod $method): void
    {
        $this->assertTrue($method->isCacheable());
    }

    #[DataProvider('provideNonCacheableMethods')]
    public function testIsCacheableWillReturnFalse(RequestMethod $method): void
    {
        $this->assertFalse($method->isCacheable());
    }

    public static function provideSafeMethods(): array
    {
        return [
            [RequestMethod::Get],
            [RequestMethod::Head],
            [RequestMethod::Options],
            [RequestMethod::Trace],
        ];
    }

    public static function provideUnsafeMethods(): array
    {
        return [
            [RequestMethod::Post],
            [RequestMethod::Put],
            [RequestMethod::Patch],
            [RequestMethod::Delete],
            [RequestMethod::Purge],
            [RequestMethod::Connect],
        ];
    }

    public static function provideIdempotentMethods(): array
    {
        return [
            [RequestMethod::Get],
            [RequestMethod::Head],
            [RequestMethod::Put],
            [RequestMethod::Delete],
            [RequestMethod::Options],
            [RequestMethod::Trace],
        ];
    }

    public static function provideNonIdempotentMethods(): array
    {
        return [
            [RequestMethod::Post],
            [RequestMethod::Patch],
            [RequestMethod::Purge],
            [RequestMethod::Connect],
        ];
    }

    public static function provideCacheableMethods(): array
    {
        return [
            [RequestMethod::Get],
            [RequestMethod::Head],
        ];
    }

    public static function provideNonCacheableMethods(): array
    {
        return [
            [RequestMethod::Post],
            [RequestMethod::Put],
            [RequestMethod::Patch],
            [RequestMethod::Delete],
            [RequestMethod::Purge],
            [RequestMethod::Options],
            [RequestMethod::Trace],
            [RequestMethod::Connect],
        ];
    }
}
