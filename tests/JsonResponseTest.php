<?php

declare(strict_types=1);

namespace FastForward\Http\Message\Tests;

use FastForward\Http\Message\JsonResponse;
use FastForward\Http\Message\JsonResponseInterface;
use FastForward\Http\Message\JsonStream;
use FastForward\Http\Message\JsonStreamInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(JsonResponse::class)]
#[UsesClass(JsonStream::class)]
final class JsonResponseTest extends TestCase
{
    public function testClassImplementsJsonResponseInterface(): void
    {
        $this->assertInstanceOf(JsonResponseInterface::class, new JsonResponse());
    }

    public function testConstructorWillInitializeWithPayload(): void
    {
        $payload = ['success' => true];

        $response = new JsonResponse($payload);

        $this->assertSame(['success' => true], $response->getPayload());
        $this->assertInstanceOf(JsonStreamInterface::class, $response->getBody());
        $this->assertSame('application/json; charset=utf-8', $response->getHeaderLine('Content-Type'));
    }

    public function testWithPayloadWillReturnNewInstanceWithNewPayload(): void
    {
        $initialPayload = ['foo' => 'bar'];
        $newPayload = ['bar' => 'baz'];

        $original = new JsonResponse($initialPayload);
        $updated = $original->withPayload($newPayload);

        $this->assertSame($initialPayload, $original->getPayload());
        $this->assertSame($newPayload, $updated->getPayload());

        $this->assertNotSame($original, $updated);
        $this->assertInstanceOf(JsonResponse::class, $updated);
    }

    public function testWithPayloadWillNotMutateOriginalResponse(): void
    {
        $payload = ['immutable' => true];

        $response = new JsonResponse($payload);
        $newPayload = ['immutable' => false];

        $newResponse = $response->withPayload($newPayload);

        $this->assertSame(['immutable' => true], $response->getPayload());
        $this->assertSame(['immutable' => false], $newResponse->getPayload());

        $this->assertNotSame($response, $newResponse);
    }
}
