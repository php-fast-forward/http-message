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

use FastForward\Http\Message\Header\ContentEncoding;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(ContentEncoding::class)]
final class ContentEncodingTest extends TestCase
{
    #[DataProvider('providerCases')]
    public function testCasesHaveCorrectValues(ContentEncoding $case, string $expected): void
    {
        self::assertSame($expected, $case->value);
    }

    #[DataProvider('providerIsSupported')]
    public function testIsSupported(ContentEncoding $encoding, string $header, bool $expected): void
    {
        self::assertSame($expected, ContentEncoding::isSupported($encoding, $header));
    }

    public static function providerCases(): array
    {
        return [
            [ContentEncoding::Gzip, 'gzip'],
            [ContentEncoding::Compress, 'compress'],
            [ContentEncoding::Deflate, 'deflate'],
            [ContentEncoding::Brotli, 'br'],
            [ContentEncoding::Zstd, 'zstd'],
            [ContentEncoding::Identity, 'identity'],
            [ContentEncoding::Dcb, 'dcb'],
            [ContentEncoding::Dcz, 'dcz'],
        ];
    }

    public static function providerIsSupported(): array
    {
        return [
            'explicitly accepted'                             => [ContentEncoding::Gzip, 'gzip, deflate, br', true],
            'another explicitly accepted'                     => [ContentEncoding::Brotli, 'gzip, deflate, br', true],
            'explicitly rejected'                             => [ContentEncoding::Gzip, 'gzip;q=0, deflate, br', false],
            'accepted by x-gzip alias'                        => [ContentEncoding::Gzip, 'x-gzip, deflate, br', true],
            'rejected by x-gzip alias'                        => [ContentEncoding::Gzip, 'x-gzip;q=0, deflate, br', false],
            'not mentioned but wildcard accepts'              => [ContentEncoding::Brotli, 'gzip, *;q=0.5', true],
            'not mentioned and wildcard rejects'              => [ContentEncoding::Brotli, 'gzip, *;q=0', false],
            'not mentioned and no wildcard (implicit accept)' => [ContentEncoding::Deflate, 'gzip, br', true],
            'empty header (implicit accept)'                  => [ContentEncoding::Gzip, '', true],
            'wildcard only'                                   => [ContentEncoding::Zstd, '*', true],
            'identity is always accepted unless q=0'          => [ContentEncoding::Identity, 'gzip, br', true],
            'identity explicitly rejected'                    => [ContentEncoding::Identity, 'identity;q=0', false],
        ];
    }
}
