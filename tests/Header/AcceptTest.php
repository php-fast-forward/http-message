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

use FastForward\Http\Message\Header\Accept;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(Accept::class)]
final class AcceptTest extends TestCase
{
    /**
     * @param string $header
     * @param array $supported
     * @param Accept|null $expected
     *
     * @return void
     */
    #[DataProvider('providerBestMatch')]
    #[Test]
    public function getBestMatchWithHeaderAndSupportedWillReturnExpectedAccept(
        string $header,
        array $supported,
        ?Accept $expected
    ): void {
        self::assertSame($expected, Accept::getBestMatch($header, $supported));
    }

    /**
     * @return void
     */
    #[Test]
    public function getBestMatchWithEqualQualityFactorsWillReturnOneOfSupported(): void
    {
        $header    = 'text/html, application/xml;q=0.9, application/json;q=0.9';
        $supported = [Accept::ApplicationJson, Accept::ApplicationXml];
        $result    = Accept::getBestMatch($header, $supported);

        self::assertNotNull($result);
        self::assertContains($result, $supported);
    }

    /**
     * @return array
     */
    public static function providerBestMatch(): array
    {
        $allSupported = [Accept::ApplicationJson, Accept::ApplicationXml, Accept::TextHtml];

        return [
            'legacy extension does not override quality' => [
                'text/html;q=0.5;note="a;q=0", application/json;q=0.2',
                $allSupported,
                Accept::TextHtml,
            ],
            'escaped quote in other range does not suppress json' => [
                'application/json, text/html;profile="a\"b"',
                [Accept::ApplicationJson],
                Accept::ApplicationJson,
            ],
            'escaped backslash before closing quote' => [
                'text/html;profile="a\\\\", application/json',
                [Accept::ApplicationJson],
                Accept::ApplicationJson,
            ],
            'uppercase media type' => ['APPLICATION/JSON', $allSupported, Accept::ApplicationJson],
            'equal quality favors specific representation' => [
                '*/*;q=0.5, text/html;q=0.5',
                $allSupported,
                Accept::TextHtml,
            ],
            'quoted comma parameter is not a new range' => [
                'text/html;profile="a,application/json"',
                $allSupported,
                null,
            ],
            'zero excluded' => ['application/json;q=0', $allSupported, null],
            'specific exclusion beats wildcard' => [
                'application/json;q=0, */*;q=1',
                [Accept::ApplicationJson, Accept::TextHtml],
                Accept::TextHtml,
            ],
            'specific quality beats wildcard quality' => [
                'text/*;q=1, text/plain;q=0.1, application/json;q=0.5',
                [Accept::TextPlain, Accept::ApplicationJson],
                Accept::ApplicationJson,
            ],
            'case insensitive type and quality name' => [
                'APPLICATION/JSON;Q=0, TEXT/HTML',
                $allSupported,
                Accept::TextHtml,
            ],
            'wildcard exclusion' => ['*/*;q=0', $allSupported, null],
            'specific acceptance overrides wildcard exclusion' => [
                '*/*;q=0, application/json;q=0.5',
                $allSupported,
                Accept::ApplicationJson,
            ],
            'parameterized range does not match bare representation' => ['text/html;level=1', [Accept::TextHtml], null],
            'parameterized exclusion does not exclude bare representation' => [
                'text/html;level=1;q=0, text/html;q=1',
                [Accept::TextHtml],
                Accept::TextHtml,
            ],
            'invalid quality above one skips range' => [
                'application/json;q=2, text/html;q=0.5',
                $allSupported,
                Accept::TextHtml,
            ],
            'non numeric quality skips range' => ['application/json;q=abc', $allSupported, null],
            'padded quality competes with higher quality' => [
                "\ttext/html ; Q=0.5, application/json;q=0.8",
                $allSupported,
                Accept::ApplicationJson,
            ],
            'empty header' => ['', $allSupported, null],
            'simple match: json' => ['application/json', $allSupported, Accept::ApplicationJson],
            'simple match: html' => ['text/html', $allSupported, Accept::TextHtml],
            'quality factor: json preferred' => [
                'text/html;q=0.8, application/json;q=0.9',
                $allSupported,
                Accept::ApplicationJson,
            ],
            'quality factor: html preferred' => [
                'text/html;q=0.9, application/json;q=0.8',
                $allSupported,
                Accept::TextHtml,
            ],
            'specificity: specific type preferred over wildcard' => [
                'application/json, */*;q=0.8',
                $allSupported,
                Accept::ApplicationJson,
            ],
            'wildcard match: any' => ['*/*', [Accept::TextPlain, Accept::ApplicationJson], Accept::TextPlain],
            'wildcard match: subtype' => [
                'text/*',
                [Accept::ApplicationJson, Accept::TextPlain],
                Accept::TextPlain,
            ],
            'no match' => ['image/png', $allSupported, null],
            'complex header' => [
                'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                [Accept::ApplicationJson, Accept::ApplicationXml],
                Accept::ApplicationXml,
            ],
            'unsupported type with high q' => [
                'image/webp;q=1.0, application/json;q=0.8',
                [Accept::ApplicationJson, Accept::TextHtml],
                Accept::ApplicationJson,
            ],
        ];
    }
}
