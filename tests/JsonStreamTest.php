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
use InvalidArgumentException;
use JsonException;
use FastForward\Http\Message\JsonStream;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(JsonStream::class)]
final class JsonStreamTest extends TestCase
{
    /**
     * @return void
     */
    #[Test]
    public function implementsJsonStreamInterfaceWillReturnInstanceOfJsonStream(): void
    {
        self::assertInstanceOf(JsonStream::class, new JsonStream());
    }

    /**
     * @return void
     */
    #[Test]
    public function constructWithPayloadWillEncodeAndPreserveDecodedValue(): void
    {
        $payload = [
            'key' => 'value',
        ];

        $stream = new JsonStream($payload);

        self::assertSame($payload, $stream->getPayload());
        self::assertSame(json_encode($payload, JsonStream::ENCODING_OPTIONS), (string) $stream);
    }

    /**
     * @return void
     */
    #[Test]
    public function withPayloadWithNewPayloadWillReturnNewInstanceWithNewPayload(): void
    {
        $initialPayload = [
            'first' => 1,
        ];
        $newPayload     = [
            'second' => 2,
        ];

        $original = new JsonStream($initialPayload);
        $updated  = $original->withPayload($newPayload);

        self::assertSame($initialPayload, $original->getPayload());
        self::assertSame($newPayload, $updated->getPayload());
        self::assertSame(json_encode($newPayload, JsonStream::ENCODING_OPTIONS), (string) $updated);
        self::assertNotSame($original, $updated);
        self::assertNotSame($original->getPayload(), $updated->getPayload());
        self::assertInstanceOf(JsonStream::class, $updated);
    }

    /**
     * @return void
     */
    #[Test]
    public function constructWithResourcePayloadWillThrowInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot JSON encode resources');

        $resource = fopen('php://temp', 'r');
        new JsonStream($resource);
    }

    /**
     * @return void
     */
    #[Test]
    public function constructWithInvalidPayloadWillThrowJsonException(): void
    {
        $this->expectException(JsonException::class);

        $data = "\xB1\x31"; // Malformed UTF-8 sequence
        new JsonStream($data);
    }

    /**
     * @return void
     */
    #[Test]
    public function withPayloadWithNewPayloadWillNotMutateOriginalInstance(): void
    {
        $payload = [
            'immutable' => true,
        ];

        $stream     = new JsonStream($payload);
        $newPayload = [
            'immutable' => false,
        ];

        $newStream = $stream->withPayload($newPayload);

        self::assertSame([
            'immutable' => true,
        ], $stream->getPayload());
        self::assertSame(json_encode([
            'immutable' => true,
        ], JsonStream::ENCODING_OPTIONS), (string) $stream);

        self::assertSame([
            'immutable' => false,
        ], $newStream->getPayload());
        self::assertSame(json_encode([
            'immutable' => false,
        ], JsonStream::ENCODING_OPTIONS), (string) $newStream);
    }
}
