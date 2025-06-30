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

use FastForward\Http\Message\StatusCode;
use FastForward\Http\Message\TextResponse;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(TextResponse::class)]
#[UsesClass(StatusCode::class)]
final class TextResponseTest extends TestCase
{
    public function testConstructorWillSetTextBodyAndContentType(): void
    {
        $text = 'Plain text response';

        $response = new TextResponse($text);

        self::assertSame(StatusCode::Ok->value, $response->getStatusCode());
        self::assertSame(StatusCode::Ok->getReasonPhrase(), $response->getReasonPhrase());
        self::assertSame('text/plain; charset=utf-8', $response->getHeaderLine('Content-Type'));
        self::assertSame($text, (string) $response->getBody());
    }

    public function testConstructorWillRespectCustomCharset(): void
    {
        $text    = 'Texto com charset';
        $charset = 'iso-8859-1';

        $response = new TextResponse($text, $charset);

        self::assertSame('text/plain; charset=iso-8859-1', $response->getHeaderLine('Content-Type'));
        self::assertSame($text, (string) $response->getBody());
    }

    public function testConstructorWillPreserveAdditionalHeaders(): void
    {
        $text    = 'Texto com cabeçalhos';
        $headers = [
            'X-Test'     => 'test-value',
            'X-Multiple' => ['um', 'dois'],
        ];

        $response = new TextResponse($text, headers: $headers);

        self::assertSame('text/plain; charset=utf-8', $response->getHeaderLine('Content-Type'));
        self::assertSame('test-value', $response->getHeaderLine('X-Test'));
        self::assertSame('um, dois', $response->getHeaderLine('X-Multiple'));
        self::assertSame($text, (string) $response->getBody());
    }
}
