<?php

declare(strict_types=1);

/**
 * This file is part of php-fast-forward/http-message.
 *
 * This source file is subject to the license bundled
 * with this source code in the file LICENSE.
 *
 * @link      https://github.com/php-fast-forward/http-message
 * @copyright Copyright (c) 2025 Felipe Sayão Lobato Abreu <github@mentordosnerds.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

namespace FastForward\Http\Message\Tests\Header;

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
    #[DataProvider('providerCases')]
    public function testCasesHaveCorrectValues(TransferEncoding $case, string $expected): void
    {
        self::assertSame($expected, $case->value);
    }

    #[DataProvider('providerIsChunked')]
    public function testIsChunked(string $header, bool $expected): void
    {
        self::assertSame($expected, TransferEncoding::isChunked($header));
    }

    public static function providerCases(): array
    {
        return [
            [TransferEncoding::Chunked, 'chunked'],
            [TransferEncoding::Compress, 'compress'],
            [TransferEncoding::Deflate, 'deflate'],
            [TransferEncoding::Gzip, 'gzip'],
        ];
    }

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
