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
use FastForward\Http\Message\RedirectResponse;
use FastForward\Http\Message\StatusCode;
use Nyholm\Psr7\Uri;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(RedirectResponse::class)]
#[UsesClass(StatusCode::class)]
final class RedirectResponseTest extends TestCase
{
    /**
     * @return void
     */
    #[Test]
    public function constructWithTemporaryLocationWillReturnResponseWithFoundStatus(): void
    {
        $uri = 'https://example.com';

        $response = new RedirectResponse($uri);

        self::assertSame(StatusCode::Found->value, $response->getStatusCode());
        self::assertSame(StatusCode::Found->getReasonPhrase(), $response->getReasonPhrase());
        self::assertSame('https://example.com', $response->getHeaderLine('Location'));
    }

    /**
     * @return void
     */
    #[Test]
    public function constructWithPermanentLocationWillReturnResponseWithMovedPermanentlyStatus(): void
    {
        $uri = 'https://example.com/redirect';

        $response = new RedirectResponse($uri, permanent: true);

        self::assertSame(StatusCode::MovedPermanently->value, $response->getStatusCode());
        self::assertSame(StatusCode::MovedPermanently->getReasonPhrase(), $response->getReasonPhrase());
        self::assertSame('https://example.com/redirect', $response->getHeaderLine('Location'));
    }

    /**
     * @return void
     */
    #[Test]
    public function constructWithUriInterfaceInstanceWillReturnResponseWithLocation(): void
    {
        $uri = new Uri('/relative/path');

        $response = new RedirectResponse($uri);

        self::assertSame('/relative/path', $response->getHeaderLine('Location'));
    }

    /**
     * @return void
     */
    #[Test]
    public function constructWithAdditionalHeadersWillReturnResponseWithHeaders(): void
    {
        $uri     = 'https://example.com';
        $headers = [
            'X-Custom-Header' => 'test-value',
        ];

        $response = new RedirectResponse($uri, permanent: false, headers: $headers);

        self::assertSame('test-value', $response->getHeaderLine('X-Custom-Header'));
        self::assertSame('https://example.com', $response->getHeaderLine('Location'));
    }
}
