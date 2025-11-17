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

use FastForward\Http\Message\Header\ContentType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(ContentType::class)]
final class ContentTypeTest extends TestCase
{
    #[DataProvider('providerHeaderStrings')]
    public function testFromHeaderString(string $header, ?ContentType $expected): void
    {
        self::assertSame($expected, ContentType::fromHeaderString($header));
    }

    #[DataProvider('providerCharsets')]
    public function testGetCharset(string $header, ?string $expectedCharset): void
    {
        self::assertSame($expectedCharset, ContentType::getCharset($header));
    }

    public function testWithCharset(): void
    {
        $expected = 'application/json; charset=utf-8';
        self::assertSame($expected, ContentType::ApplicationJson->withCharset('utf-8'));
    }

    #[DataProvider('providerJsonCases')]
    public function testIsJson(ContentType $case, bool $expected): void
    {
        self::assertSame($expected, $case->isJson());
    }

    #[DataProvider('providerXmlCases')]
    public function testIsXml(ContentType $case, bool $expected): void
    {
        self::assertSame($expected, $case->isXml());
    }

    #[DataProvider('providerTextCases')]
    public function testIsText(ContentType $case, bool $expected): void
    {
        self::assertSame($expected, $case->isText());
    }

    #[DataProvider('providerMultipartCases')]
    public function testIsMultipart(ContentType $case, bool $expected): void
    {
        self::assertSame($expected, $case->isMultipart());
    }

    public static function providerHeaderStrings(): array
    {
        return [
            'json with charset'    => ['application/json; charset=utf-8', ContentType::ApplicationJson],
            'html without charset' => ['text/html', ContentType::TextHtml],
            'invalid type'         => ['application/invalid', null],
            'empty string'         => ['', null],
        ];
    }

    public static function providerCharsets(): array
    {
        return [
            'json with utf-8'            => ['application/json; charset=utf-8', 'utf-8'],
            'html with iso'              => ['text/html; charset=ISO-8859-1', 'ISO-8859-1'],
            'plain text without charset' => ['text/plain', null],
            'header with extra spaces'   => [' application/json ;  charset=UTF-8 ', 'UTF-8'],
            'case insensitive'           => ['application/json; CharSet=UTF-8', 'UTF-8'],
            'empty header'               => ['', null],
        ];
    }

    public static function providerJsonCases(): array
    {
        return [
            [ContentType::ApplicationJson, true],
            [ContentType::TextHtml, false],
            [ContentType::ApplicationXml, false],
        ];
    }

    public static function providerXmlCases(): array
    {
        return [
            [ContentType::ApplicationXml, true],
            [ContentType::TextXml, true],
            [ContentType::ApplicationJson, false],
            [ContentType::TextHtml, false],
        ];
    }

    public static function providerTextCases(): array
    {
        return [
            [ContentType::TextPlain, true],
            [ContentType::TextHtml, true],
            [ContentType::TextCss, true],
            [ContentType::TextCsv, true],
            [ContentType::TextXml, true],
            [ContentType::ApplicationJson, false],
            [ContentType::ImagePng, false],
        ];
    }

    public static function providerMultipartCases(): array
    {
        return [
            [ContentType::MultipartFormData, true],
            [ContentType::ApplicationFormUrlencoded, false],
            [ContentType::ApplicationJson, false],
        ];
    }
}
