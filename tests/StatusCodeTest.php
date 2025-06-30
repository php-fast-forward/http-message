<?php

declare(strict_types=1);

namespace FastForward\Http\Message\Tests;

use FastForward\Http\Message\StatusCode;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(StatusCode::class)]
final class StatusCodeTest extends TestCase
{
    #[DataProvider('provideStatusCodes')]
    public function testGetCodeWillReturnExpectedValue(StatusCode $statusCode, int $expected): void
    {
        $this->assertSame($expected, $statusCode->getCode());
    }

    #[DataProvider('provideReasonPhrases')]
    public function testGetReasonPhraseWillReturnExpectedValue(StatusCode $statusCode, string $expected): void
    {
        $this->assertSame($expected, $statusCode->getReasonPhrase());
    }

    #[DataProvider('provideCategories')]
    public function testGetCategoryWillReturnExpectedValue(StatusCode $statusCode, string $expected): void
    {
        $this->assertSame($expected, $statusCode->getCategory());
    }

    #[DataProvider('provideInformationalStatusCodes')]
    public function testIsInformationalWillReturnTrue(StatusCode $statusCode): void
    {
        $this->assertTrue($statusCode->isInformational());
    }

    #[DataProvider('provideSuccessStatusCodes')]
    public function testIsSuccessWillReturnTrue(StatusCode $statusCode): void
    {
        $this->assertTrue($statusCode->isSuccess());
    }

    #[DataProvider('provideRedirectionStatusCodes')]
    public function testIsRedirectionWillReturnTrue(StatusCode $statusCode): void
    {
        $this->assertTrue($statusCode->isRedirection());
    }

    #[DataProvider('provideClientErrorStatusCodes')]
    public function testIsClientErrorWillReturnTrue(StatusCode $statusCode): void
    {
        $this->assertTrue($statusCode->isClientError());
        $this->assertTrue($statusCode->isError());
    }

    #[DataProvider('provideServerErrorStatusCodes')]
    public function testIsServerErrorWillReturnTrue(StatusCode $statusCode): void
    {
        $this->assertTrue($statusCode->isServerError());
        $this->assertTrue($statusCode->isError());
    }

    public static function provideStatusCodes(): array
    {
        return [
            [StatusCode::Ok, 200],
            [StatusCode::BadRequest, 400],
            [StatusCode::InternalServerError, 500],
        ];
    }

    public static function provideReasonPhrases(): array
    {
        return [
            [StatusCode::Ok, 'Ok'],
            [StatusCode::BadRequest, 'Bad Request'],
            [StatusCode::InternalServerError, 'Internal Server Error'],
        ];
    }

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

    public static function provideInformationalStatusCodes(): array
    {
        return [
            [StatusCode::Continue],
            [StatusCode::Processing],
        ];
    }

    public static function provideSuccessStatusCodes(): array
    {
        return [
            [StatusCode::Ok],
            [StatusCode::Created],
        ];
    }

    public static function provideRedirectionStatusCodes(): array
    {
        return [
            [StatusCode::MovedPermanently],
            [StatusCode::Found],
        ];
    }

    public static function provideClientErrorStatusCodes(): array
    {
        return [
            [StatusCode::BadRequest],
            [StatusCode::Unauthorized],
        ];
    }

    public static function provideServerErrorStatusCodes(): array
    {
        return [
            [StatusCode::InternalServerError],
            [StatusCode::ServiceUnavailable],
        ];
    }
}
