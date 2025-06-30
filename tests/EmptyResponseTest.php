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

use FastForward\Http\Message\EmptyResponse;
use FastForward\Http\Message\StatusCode;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(EmptyResponse::class)]
#[UsesClass(StatusCode::class)]
final class EmptyResponseTest extends TestCase
{
    public function testConstructorWillSetNoContentStatusAndEmptyBody(): void
    {
        $response = new EmptyResponse();

        self::assertSame(StatusCode::NoContent->value, $response->getStatusCode());
        self::assertSame(StatusCode::NoContent->getReasonPhrase(), $response->getReasonPhrase());
        self::assertSame('', (string) $response->getBody());
    }

    public function testConstructorWillPreserveAdditionalHeaders(): void
    {
        $headers = [
            'X-Test-Header' => 'value',
            'X-Multiple'    => ['one', 'two'],
        ];

        $response = new EmptyResponse($headers);

        self::assertSame('value', $response->getHeaderLine('X-Test-Header'));
        self::assertSame('one, two', $response->getHeaderLine('X-Multiple'));
        self::assertSame(StatusCode::NoContent->value, $response->getStatusCode());
        self::assertSame('', (string) $response->getBody());
    }
}
