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

    #[Test]
    public function genericJsonBodyIsDecodedWithoutMovingItsCursor(): void
    {
        $original = new JsonResponse(['original' => true]);
        $stream = \Nyholm\Psr7\Stream::create('{"replacement":true}');
        $stream->seek(5);
        $replacement = $original->withBody($stream);

        self::assertSame(['replacement' => true], $replacement->getPayload());
        self::assertSame(5, $stream->tell());
        self::assertSame(['original' => true], $original->getPayload());
        $updated = $replacement->withPayload(['next' => true]);
        self::assertSame(['next' => true], $updated->getPayload());
        self::assertSame($stream, $replacement->getBody());
    }

    #[Test]
    public function invalidGenericBodyHasAnExplicitJsonErrorAndRemainsReplaceable(): void
    {
        $stream = \Nyholm\Psr7\Stream::create('not json');
        $stream->seek(2);
        $response = (new JsonResponse())->withBody($stream);
        self::assertSame(['recovered' => true], $response->withPayload(['recovered' => true])->getPayload());
        try {
            $response->getPayload();
            self::fail('Invalid JSON must not be silently accepted.');
        } catch (\JsonException) {
            self::assertSame(2, $stream->tell());
        }
    }

    #[Test]
    public function detachedGenericBodyHasAnExplicitStreamErrorAndRemainsReplaceable(): void
    {
        $stream = \Nyholm\Psr7\Stream::create('{}');
        fclose($stream->detach());
        $response = (new JsonResponse())->withBody($stream);
        self::assertSame('recovered', $response->withPayload('recovered')->getPayload());
        $this->expectException(\RuntimeException::class);
        $response->getPayload();
    }

    #[Test]
    public function genericJsonNullIsNotConfusedWithInvalidJson(): void
    {
        $response = (new JsonResponse())->withBody(\Nyholm\Psr7\Stream::create('null'));
        self::assertNull($response->getPayload());
    }

    #[Test]
    public function nonSeekableGenericBodyIsNotConsumed(): void
    {
        $body = $this->createMock(\Psr\Http\Message\StreamInterface::class);
        $body->method('isReadable')->willReturn(true);
        $body->method('isSeekable')->willReturn(false);
        $body->expects(self::never())->method('getContents');
        $response = (new JsonResponse())->withBody($body);
        self::assertSame(['new' => true], $response->withPayload(['new' => true])->getPayload());
        $this->expectException(\RuntimeException::class);
        $response->getPayload();
    }

    #[Test]
    public function payloadReplacementPreservesExistingJsonStreamEncodingOptionsAndHeaders(): void
    {
        $stream = new \FastForward\Http\Message\JsonStream(['old' => true], \JSON_PRETTY_PRINT);
        $response = (new JsonResponse([], 'iso-8859-1'))->withBody($stream);
        $updated = $response->withPayload(['new' => true]);
        self::assertStringContainsString("\n", (string) $updated->getBody());
        self::assertSame($response->getHeaders(), $updated->getHeaders());
        self::assertSame(['old' => true], $response->getPayload());
    }
}
