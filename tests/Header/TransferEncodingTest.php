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

namespace FastForward\Http\Message\Tests\Header;

use PHPUnit\Framework\Attributes\Test;
use FastForward\Http\Message\Header\TransferEncoding;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(TransferEncoding::class)]
final class TransferEncodingTest extends TestCase
{
    /**
     * @param TransferEncoding $case
     * @param string $expected
     *
     * @return void
     */
    #[DataProvider('providerCases')]
    #[Test]
    public function valueWithCaseWillReturnExpectedValue(TransferEncoding $case, string $expected): void
    {
        self::assertSame($expected, $case->value);
    }

    /**
     * @param string $header
     * @param bool $expected
     *
     * @return void
     */
    #[DataProvider('providerIsChunked')]
    #[Test]
    public function isChunkedWithHeaderWillReturnExpected(string $header, bool $expected): void
    {
        self::assertSame($expected, TransferEncoding::isChunked($header));
    }

    /**
     * @return array
     */
    public static function providerCases(): array
    {
        return [
            [TransferEncoding::Chunked, 'chunked'],
            [TransferEncoding::Compress, 'compress'],
            [TransferEncoding::Deflate, 'deflate'],
            [TransferEncoding::Gzip, 'gzip'],
        ];
    }

    /**
     * @return array
     */
    public static function providerIsChunked(): array
    {
        return [
            'only chunked'     => ['chunked', true],
            'chunked last'     => ['gzip, chunked', true],
            'case insensitive' => ['GZIP, CHUNKED', true],
            'with spaces'      => [' gzip , chunked ', true],
            'not chunked'      => ['gzip, deflate', false],
            'empty header'     => ['', false],
        ];
    }
}
