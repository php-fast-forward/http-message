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

use FastForward\Http\Message\HtmlResponse;
use FastForward\Http\Message\StatusCode;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(HtmlResponse::class)]
#[UsesClass(StatusCode::class)]
final class HtmlResponseTest extends TestCase
{
    public function testConstructorWillSetHtmlBodyAndContentType(): void
    {
        $html = '<h1>Hello World</h1>';

        $response = new HtmlResponse($html);

        self::assertSame(StatusCode::Ok->value, $response->getStatusCode());
        self::assertSame(StatusCode::Ok->getReasonPhrase(), $response->getReasonPhrase());
        self::assertSame('text/html; charset=utf-8', $response->getHeaderLine('Content-Type'));
        self::assertSame($html, (string) $response->getBody());
    }

    public function testConstructorWillRespectCustomCharset(): void
    {
        $html = '<p>Charset Test</p>';

        $response = new HtmlResponse($html, charset: 'iso-8859-1');

        self::assertSame('text/html; charset=iso-8859-1', $response->getHeaderLine('Content-Type'));
        self::assertSame($html, (string) $response->getBody());
    }

    public function testConstructorWillPreserveAdditionalHeaders(): void
    {
        $html    = '<div>Content</div>';
        $headers = [
            'X-Test'  => 'test-value',
            'X-Array' => ['one', 'two'],
        ];

        $response = new HtmlResponse($html, headers: $headers);

        self::assertSame('text/html; charset=utf-8', $response->getHeaderLine('Content-Type'));
        self::assertSame('test-value', $response->getHeaderLine('X-Test'));
        self::assertSame('one, two', $response->getHeaderLine('X-Array'));
        self::assertSame($html, (string) $response->getBody());
    }
}
