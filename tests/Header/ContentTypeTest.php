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

use FastForward\Http\Message\Header\ContentType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(ContentType::class)]
final class ContentTypeTest extends TestCase
{
    /**
     * @param string $header
     * @param ContentType|null $expected
     *
     * @return void
     */
    #[DataProvider('providerHeaderStrings')]
    #[Test]
    public function fromHeaderStringWithHeaderWillReturnExpectedContentType(
        string $header,
        ?ContentType $expected
    ): void {
        self::assertSame($expected, ContentType::fromHeaderString($header));
    }

    /**
     * @param string $header
     * @param string|null $expectedCharset
     *
     * @return void
     */
    #[DataProvider('providerCharsets')]
    #[Test]
    public function getCharsetWithHeaderWillReturnExpectedCharset(string $header, ?string $expectedCharset): void
    {
        self::assertSame($expectedCharset, ContentType::getCharset($header));
    }

    /**
     * @return void
     */
    #[Test]
    public function withCharsetWithUtf8WillReturnExpectedString(): void
    {
        $expected = 'application/json; charset=utf-8';
        self::assertSame($expected, ContentType::ApplicationJson->withCharset('utf-8'));
    }

    /**
     * @param ContentType $case
     * @param bool $expected
     *
     * @return void
     */
    #[DataProvider('providerJsonCases')]
    #[Test]
    public function isJsonWithCaseWillReturnExpected(ContentType $case, bool $expected): void
    {
        self::assertSame($expected, $case->isJson());
    }

    /**
     * @param ContentType $case
     * @param bool $expected
     *
     * @return void
     */
    #[DataProvider('providerXmlCases')]
    #[Test]
    public function isXmlWithCaseWillReturnExpected(ContentType $case, bool $expected): void
    {
        self::assertSame($expected, $case->isXml());
    }

    /**
     * @param ContentType $case
     * @param bool $expected
     *
     * @return void
     */
    #[DataProvider('providerTextCases')]
    #[Test]
    public function isTextWithCaseWillReturnExpected(ContentType $case, bool $expected): void
    {
        self::assertSame($expected, $case->isText());
    }

    /**
     * @param ContentType $case
     * @param bool $expected
     *
     * @return void
     */
    #[DataProvider('providerMultipartCases')]
    #[Test]
    public function isMultipartWithCaseWillReturnExpected(ContentType $case, bool $expected): void
    {
        self::assertSame($expected, $case->isMultipart());
    }

    /**
     * @return array
     */
    public static function providerHeaderStrings(): array
    {
        return [
            'json with charset'    => ['application/json; charset=utf-8', ContentType::ApplicationJson],
            'html without charset' => ['text/html', ContentType::TextHtml],
            'invalid type'         => ['application/invalid', null],
            'empty string'         => ['', null],
        ];
    }

    /**
     * @return array
     */
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

    /**
     * @return array
     */
    public static function providerJsonCases(): array
    {
        return [
            [ContentType::ApplicationJson, true],
            [ContentType::TextHtml, false],
            [ContentType::ApplicationXml, false],
        ];
    }

    /**
     * @return array
     */
    public static function providerXmlCases(): array
    {
        return [
            [ContentType::ApplicationXml, true],
            [ContentType::TextXml, true],
            [ContentType::ApplicationJson, false],
            [ContentType::TextHtml, false],
        ];
    }

    /**
     * @return array
     */
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

    /**
     * @return array
     */
    public static function providerMultipartCases(): array
    {
        return [
            [ContentType::MultipartFormData, true],
            [ContentType::ApplicationFormUrlencoded, false],
            [ContentType::ApplicationJson, false],
        ];
    }
}
