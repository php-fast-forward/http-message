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
use FastForward\Http\Message\Header\ContentType;
use FastForward\Http\Message\JsonResponse;
use FastForward\Http\Message\JsonStream;
use FastForward\Http\Message\PayloadResponseInterface;
use FastForward\Http\Message\PayloadStreamInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(JsonResponse::class)]
#[UsesClass(JsonStream::class)]
#[UsesClass(ContentType::class)]
final class JsonResponseTest extends TestCase
{
    /**
     * @return void
     */
    #[Test]
    public function implementsJsonResponseInterfaceWillReturnInstanceOfPayloadResponseInterface(): void
    {
        self::assertInstanceOf(PayloadResponseInterface::class, new JsonResponse());
    }

    /**
     * @param string $charset
     *
     * @return void
     */
    #[DataProvider('providerCharsets')]
    #[Test]
    public function constructWithPayloadAndCharsetWillReturnResponseWithPayloadAndCharset(string $charset): void
    {
        $payload = [
            'success' => (bool) random_int(0, 1),
            'timestamp' => time(),
        ];

        $response = new JsonResponse($payload, $charset);

        self::assertSame($payload, $response->getPayload());
        self::assertInstanceOf(PayloadStreamInterface::class, $response->getBody());
        self::assertSame(
            ContentType::ApplicationJson,
            ContentType::fromHeaderString($response->getHeaderLine('Content-Type'))
        );
        self::assertSame($charset, ContentType::getCharset($response->getHeaderLine('Content-Type')));
    }

    /**
     * @return void
     */
    #[Test]
    public function withPayloadWithNewPayloadWillReturnNewInstanceWithNewPayload(): void
    {
        $initialPayload = [
            'foo' => 'bar',
        ];
        $newPayload     = [
            'bar' => 'baz',
        ];

        $original = new JsonResponse($initialPayload);
        $updated  = $original->withPayload($newPayload);

        self::assertSame($initialPayload, $original->getPayload());
        self::assertSame($newPayload, $updated->getPayload());

        self::assertNotSame($original, $updated);
        self::assertInstanceOf(JsonResponse::class, $updated);
    }

    /**
     * @return void
     */
    #[Test]
    public function withPayloadWithNewPayloadWillNotMutateOriginalResponse(): void
    {
        $payload = [
            'immutable' => true,
        ];

        $response   = new JsonResponse($payload);
        $newPayload = [
            'immutable' => false,
        ];

        $newResponse = $response->withPayload($newPayload);

        self::assertSame([
            'immutable' => true,
        ], $response->getPayload());
        self::assertSame([
            'immutable' => false,
        ], $newResponse->getPayload());

        self::assertNotSame($response, $newResponse);
    }

    /**
     * @return array
     */
    public static function providerCharsets(): array
    {
        return [
            'default charset' => ['utf-8'],
            'custom charset'  => ['iso-8859-1'],
        ];
    }
}
