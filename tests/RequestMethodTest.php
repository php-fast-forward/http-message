<?php

declare(strict_types=1);

/**
 * This file is part of php-fast-forward/http-message.
 *
 * This source file is subject to the license bundled
 * with this source code in the file LICENSE.
 *
 * @copyright Copyright (c) 2025-2026 Felipe Sayão Lobato Abreu <github@mentordosnerds.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 *
 * @see       https://github.com/php-fast-forward/http-message
 * @see       https://github.com/php-fast-forward
 * @see       https://datatracker.ietf.org/doc/html/rfc2119
 */

namespace Tests\FastForward\Http\Message;

use PHPUnit\Framework\Attributes\Test;
use FastForward\Http\Message\RequestMethod;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(RequestMethod::class)]
final class RequestMethodTest extends TestCase
{
    /**
     * @param RequestMethod $method
     *
     * @return void
     */
    #[DataProvider('provideSafeMethods')]
    #[Test]
    public function isSafeWithSafeMethodWillReturnTrue(RequestMethod $method): void
    {
        self::assertTrue($method->isSafe());
    }

    /**
     * @param RequestMethod $method
     *
     * @return void
     */
    #[DataProvider('provideUnsafeMethods')]
    #[Test]
    public function isSafeWithUnsafeMethodWillReturnFalse(RequestMethod $method): void
    {
        self::assertFalse($method->isSafe());
    }

    /**
     * @param RequestMethod $method
     *
     * @return void
     */
    #[DataProvider('provideIdempotentMethods')]
    #[Test]
    public function isIdempotentWithIdempotentMethodWillReturnTrue(RequestMethod $method): void
    {
        self::assertTrue($method->isIdempotent());
    }

    /**
     * @param RequestMethod $method
     *
     * @return void
     */
    #[DataProvider('provideNonIdempotentMethods')]
    #[Test]
    public function isIdempotentWithNonIdempotentMethodWillReturnFalse(RequestMethod $method): void
    {
        self::assertFalse($method->isIdempotent());
    }

    /**
     * @param RequestMethod $method
     *
     * @return void
     */
    #[DataProvider('provideCacheableMethods')]
    public function testIsCacheableWillReturnTrue(RequestMethod $method): void
    {
        self::assertTrue($method->isCacheable());
    }

    /**
     * @param RequestMethod $method
     *
     * @return void
     */
    #[DataProvider('provideNonCacheableMethods')]
    public function testIsCacheableWillReturnFalse(RequestMethod $method): void
    {
        self::assertFalse($method->isCacheable());
    }

    /**
     * @return array
     */
    public static function provideSafeMethods(): array
    {
        return [[RequestMethod::Get], [RequestMethod::Head], [RequestMethod::Options], [RequestMethod::Trace]];
    }

    /**
     * @return array
     */
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

    /**
     * @return array
     */
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

    /**
     * @return array
     */
    public static function provideNonIdempotentMethods(): array
    {
        return [[RequestMethod::Post], [RequestMethod::Patch], [RequestMethod::Purge], [RequestMethod::Connect]];
    }

    /**
     * @return array
     */
    public static function provideCacheableMethods(): array
    {
        return [[RequestMethod::Get], [RequestMethod::Head]];
    }

    /**
     * @return array
     */
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
