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

namespace FastForward\Http\Message\Tests;

use PHPUnit\Framework\Attributes\Test;
use FastForward\Http\Message\StatusCode;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(StatusCode::class)]
final class StatusCodeTest extends TestCase
{
    /**
     * @param StatusCode $statusCode
     * @param int $expected
     *
     * @return void
     */
    #[DataProvider('provideStatusCodes')]
    #[Test]
    public function getCodeWithStatusCodeWillReturnExpectedValue(StatusCode $statusCode, int $expected): void
    {
        self::assertSame($expected, $statusCode->getCode());
    }

    /**
     * @param StatusCode $statusCode
     * @param string $expected
     *
     * @return void
     */
    #[DataProvider('provideReasonPhrases')]
    #[Test]
    public function getReasonPhraseWithStatusCodeWillReturnExpectedValue(StatusCode $statusCode, string $expected): void
    {
        self::assertSame($expected, $statusCode->getReasonPhrase());
    }

    /**
     * @param StatusCode $statusCode
     * @param string $expected
     *
     * @return void
     */
    #[DataProvider('provideCategories')]
    #[Test]
    public function getCategoryWithStatusCodeWillReturnExpectedValue(StatusCode $statusCode, string $expected): void
    {
        self::assertSame($expected, $statusCode->getCategory());
    }

    /**
     * @param StatusCode $statusCode
     *
     * @return void
     */
    #[DataProvider('provideInformationalStatusCodes')]
    #[Test]
    public function isInformationalWithStatusCodeWillReturnTrue(StatusCode $statusCode): void
    {
        self::assertTrue($statusCode->isInformational());
    }

    /**
     * @param StatusCode $statusCode
     *
     * @return void
     */
    #[DataProvider('provideSuccessStatusCodes')]
    #[Test]
    public function isSuccessWithStatusCodeWillReturnTrue(StatusCode $statusCode): void
    {
        self::assertTrue($statusCode->isSuccess());
    }

    /**
     * @param StatusCode $statusCode
     *
     * @return void
     */
    #[DataProvider('provideRedirectionStatusCodes')]
    #[Test]
    public function isRedirectionWithStatusCodeWillReturnTrue(StatusCode $statusCode): void
    {
        self::assertTrue($statusCode->isRedirection());
    }

    /**
     * @param StatusCode $statusCode
     *
     * @return void
     */
    #[DataProvider('provideClientErrorStatusCodes')]
    #[Test]
    public function isClientErrorWithStatusCodeWillReturnTrue(StatusCode $statusCode): void
    {
        self::assertTrue($statusCode->isClientError());
        self::assertTrue($statusCode->isError());
    }

    /**
     * @param StatusCode $statusCode
     *
     * @return void
     */
    #[DataProvider('provideServerErrorStatusCodes')]
    #[Test]
    public function isServerErrorWithStatusCodeWillReturnTrue(StatusCode $statusCode): void
    {
        self::assertTrue($statusCode->isServerError());
        self::assertTrue($statusCode->isError());
    }

    /**
     * @return array
     */
    public static function provideStatusCodes(): array
    {
        return [[StatusCode::Ok, 200], [StatusCode::BadRequest, 400], [StatusCode::InternalServerError, 500]];
    }

    /**
     * @return array
     */
    public static function provideReasonPhrases(): array
    {
        return [
            [StatusCode::Ok, 'Ok'],
            [StatusCode::BadRequest, 'Bad Request'],
            [StatusCode::InternalServerError, 'Internal Server Error'],
        ];
    }

    /**
     * @return array
     */
    public static function provideCategories(): array
    {
        return [
            [StatusCode::Continue, 'Informational'],
            [StatusCode::Ok, 'Success'],
            [StatusCode::TemporaryRedirect, 'Redirection'],
            [StatusCode::NotFound, 'Client Error'],
            [StatusCode::ServiceUnavailable, 'Server Error'],
        ];
    }

    /**
     * @return array
     */
    public static function provideInformationalStatusCodes(): array
    {
        return [[StatusCode::Continue], [StatusCode::Processing]];
    }

    /**
     * @return array
     */
    public static function provideSuccessStatusCodes(): array
    {
        return [[StatusCode::Ok], [StatusCode::Created]];
    }

    /**
     * @return array
     */
    public static function provideRedirectionStatusCodes(): array
    {
        return [[StatusCode::MovedPermanently], [StatusCode::Found]];
    }

    /**
     * @return array
     */
    public static function provideClientErrorStatusCodes(): array
    {
        return [[StatusCode::BadRequest], [StatusCode::Unauthorized]];
    }

    /**
     * @return array
     */
    public static function provideServerErrorStatusCodes(): array
    {
        return [[StatusCode::InternalServerError], [StatusCode::ServiceUnavailable]];
    }
}
