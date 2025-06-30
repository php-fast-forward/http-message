<?php

declare(strict_types=1);

namespace FastForward\Http\Message\Tests;

use FastForward\Http\Message\JsonStream;
use JsonException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(JsonStream::class)]
final class JsonStreamTest extends TestCase
{
    public function testClassImplementsJsonStreamInterface(): void
    {
        $this->assertInstanceOf(JsonStream::class, new JsonStream());
    }

    public function testConstructorWillEncodePayloadAndPreserveDecodedValue(): void
    {
        $payload = ['key' => 'value'];

        $stream = new JsonStream($payload);

        $this->assertSame($payload, $stream->getPayload());
        $this->assertSame(json_encode($payload, JsonStream::ENCODING_OPTIONS), (string) $stream);
    }

    public function testWithPayloadWillReturnNewInstanceWithNewPayload(): void
    {
        $initialPayload = ['first' => 1];
        $newPayload = ['second' => 2];

        $original = new JsonStream($initialPayload);
        $updated = $original->withPayload($newPayload);

        $this->assertSame($initialPayload, $original->getPayload());
        $this->assertSame($newPayload, $updated->getPayload());
        $this->assertSame(json_encode($newPayload, JsonStream::ENCODING_OPTIONS), (string) $updated);
        $this->assertNotSame($original, $updated);
        $this->assertNotSame($original->getPayload(), $updated->getPayload());
        $this->assertInstanceOf(JsonStream::class, $updated);
    }

    public function testConstructorWillThrowExceptionWhenPayloadIsResource(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot JSON encode resources');

        $resource = fopen('php://temp', 'rb');
        new JsonStream($resource);
    }

    public function testConstructorWillThrowJsonExceptionOnInvalidPayload(): void
    {
        $this->expectException(JsonException::class);

        $data = "\xB1\x31"; // Malformed UTF-8 sequence
        new JsonStream($data);
    }

    public function testWithPayloadWillNotMutateOriginalInstance(): void
    {
        $payload = ['immutable' => true];

        $stream = new JsonStream($payload);
        $newPayload = ['immutable' => false];

        $newStream = $stream->withPayload($newPayload);

        $this->assertSame(['immutable' => true], $stream->getPayload());
        $this->assertSame(json_encode(['immutable' => true], JsonStream::ENCODING_OPTIONS), (string) $stream);

        $this->assertSame(['immutable' => false], $newStream->getPayload());
        $this->assertSame(json_encode(['immutable' => false], JsonStream::ENCODING_OPTIONS), (string) $newStream);
    }
}
