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

namespace FastForward\Http\Message\Tests\Header\Authorization;

use FastForward\Http\Message\Header\Authorization\DigestCredential;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(DigestCredential::class)]
final class DigestCredentialTest extends TestCase
{
    public function testConstructorAndProperties(): void
    {
        $credential = new DigestCredential(
            username: 'Mufasa',
            realm: 'testrealm@host.com',
            nonce: 'dcd98b7102dd2f0e8b11d0f600bfb0c093',
            uri: '/dir/index.html',
            response: '6629fae49393a05397450978507c43ef',
            qop: 'auth',
            nc: '0000000000000001',
            cnonce: '0a4f113b',
            opaque: '5ccc069c403eb9f0171a9517f4041dbb',
            algorithm: 'MD5',
        );

        self::assertSame('Mufasa', $credential->username);
        self::assertSame('testrealm@host.com', $credential->realm);
        self::assertSame('dcd98b7102dd2f0e8b11d0f600bfb0c093', $credential->nonce);
        self::assertSame('/dir/index.html', $credential->uri);
        self::assertSame('6629fae49393a05397450978507c43ef', $credential->response);
        self::assertSame('auth', $credential->qop);
        self::assertSame('0000000000000001', $credential->nc);
        self::assertSame('0a4f113b', $credential->cnonce);
        self::assertSame('5ccc069c403eb9f0171a9517f4041dbb', $credential->opaque);
        self::assertSame('MD5', $credential->algorithm);
    }

    public function testConstructorWithOptionalProperties(): void
    {
        $credential = new DigestCredential(
            username: 'Mufasa',
            realm: 'testrealm@host.com',
            nonce: 'dcd98b7102dd2f0e8b11d0f600bfb0c093',
            uri: '/dir/index.html',
            response: '6629fae49393a05397450978507c43ef',
            qop: 'auth',
            nc: '0000000000000001',
            cnonce: '0a4f113b',
        );

        self::assertNull($credential->opaque);
        self::assertNull($credential->algorithm);
    }
}
