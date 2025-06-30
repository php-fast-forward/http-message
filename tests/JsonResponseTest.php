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

namespace FastForward\Http\Message\Tests;

use FastForward\Http\Message\JsonResponse;
use FastForward\Http\Message\JsonResponseInterface;
use FastForward\Http\Message\JsonStream;
use FastForward\Http\Message\JsonStreamInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(JsonResponse::class)]
#[UsesClass(JsonStream::class)]
final class JsonResponseTest extends TestCase
{
    public function testClassImplementsJsonResponseInterface(): void
    {
        self::assertInstanceOf(JsonResponseInterface::class, new JsonResponse());
    }

    public function testConstructorWillInitializeWithPayload(): void
    {
        $payload = ['success' => true];

        $response = new JsonResponse($payload);

        self::assertSame(['success' => true], $response->getPayload());
        self::assertInstanceOf(JsonStreamInterface::class, $response->getBody());
        self::assertSame('application/json; charset=utf-8', $response->getHeaderLine('Content-Type'));
    }

    public function testWithPayloadWillReturnNewInstanceWithNewPayload(): void
    {
        $initialPayload = ['foo' => 'bar'];
        $newPayload     = ['bar' => 'baz'];

        $original = new JsonResponse($initialPayload);
        $updated  = $original->withPayload($newPayload);

        self::assertSame($initialPayload, $original->getPayload());
        self::assertSame($newPayload, $updated->getPayload());

        self::assertNotSame($original, $updated);
        self::assertInstanceOf(JsonResponse::class, $updated);
    }

    public function testWithPayloadWillNotMutateOriginalResponse(): void
    {
        $payload = ['immutable' => true];

        $response   = new JsonResponse($payload);
        $newPayload = ['immutable' => false];

        $newResponse = $response->withPayload($newPayload);

        self::assertSame(['immutable' => true], $response->getPayload());
        self::assertSame(['immutable' => false], $newResponse->getPayload());

        self::assertNotSame($response, $newResponse);
    }
}
