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

namespace FastForward\Http\Message\Tests\Header;

use FastForward\Http\Message\Header\Authorization;
use FastForward\Http\Message\Header\Authorization\ApiKeyCredential;
use FastForward\Http\Message\Header\Authorization\AwsCredential;
use FastForward\Http\Message\Header\Authorization\BasicCredential;
use FastForward\Http\Message\Header\Authorization\BearerCredential;
use FastForward\Http\Message\Header\Authorization\DigestCredential;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * @internal
 */
#[CoversClass(Authorization::class)]
#[UsesClass(BasicCredential::class)]
#[UsesClass(BearerCredential::class)]
#[UsesClass(ApiKeyCredential::class)]
#[UsesClass(DigestCredential::class)]
#[UsesClass(AwsCredential::class)]
final class AuthorizationTest extends TestCase
{
    #[Covers(Authorization::class, 'parseApiKey')]
    #[Covers(Authorization::class, 'parseBasic')]
    #[Covers(Authorization::class, 'parseBearer')]
    #[Covers(Authorization::class, 'parseDigest')]
    #[Covers(Authorization::class, 'parseAws')]
    #[DataProvider('providerParse')]
    public function testParse(string $header, $expected): void
    {
        $result = Authorization::parse($header);

        if (null === $expected) {
            self::assertNull($result);
        } else {
            self::assertInstanceOf($expected['class'], $result);
            foreach ($expected['properties'] as $property => $value) {
                self::assertSame($value, $result->{$property}, "Property '{$property}' does not match expected value.");
            }
        }
    }

    #[Covers(Authorization::class, 'fromHeaderCollection')]
    #[DataProvider('providerFromHeaderCollection')]
    public function testFromHeaderCollection(array $headers, $expected): void
    {
        $result = Authorization::fromHeaderCollection($headers);

        if (null === $expected) {
            self::assertNull($result);
        } else {
            self::assertInstanceOf($expected['class'], $result);
            foreach ($expected['properties'] as $property => $value) {
                self::assertSame($value, $result->{$property}, "Property '{$property}' does not match expected value.");
            }
        }
    }

    #[Covers(Authorization::class, 'fromRequest')]
    #[DataProvider('providerFromRequest')]
    public function testFromRequest(array $headers, $expected): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request->method('getHeaders')->willReturn($headers);

        $result = Authorization::fromRequest($request);

        if (null === $expected) {
            self::assertNull($result);
        } else {
            self::assertInstanceOf($expected['class'], $result);
            foreach ($expected['properties'] as $property => $value) {
                self::assertSame($value, $result->{$property}, "Property '{$property}' does not match expected value.");
            }
        }
    }

    public static function providerParse(): array
    {
        return [
            'valid api key' => [
                'ApiKey my-super-secret-key',
                [
                    'class'      => ApiKeyCredential::class,
                    'properties' => ['key' => 'my-super-secret-key'],
                ],
            ],
            'valid basic' => [
                'Basic dXNlcjpwYXNz', // user:pass
                [
                    'class'      => BasicCredential::class,
                    'properties' => ['username' => 'user', 'password' => 'pass'],
                ],
            ],
            'valid basic with empty password' => [
                'Basic dXNlcjo=', // user:
                [
                    'class'      => BasicCredential::class,
                    'properties' => ['username' => 'user', 'password' => ''],
                ],
            ],
            'valid bearer' => [
                'Bearer my-secret-token',
                [
                    'class'      => BearerCredential::class,
                    'properties' => ['token' => 'my-secret-token'],
                ],
            ],
            'valid digest' => [
                'Digest username="Mufasa", realm="testrealm@host.com", nonce="dcd98b7102dd2f0e8b11d0f600bfb0c093", uri="/dir/index.html", qop=auth, nc=0000000000000001, cnonce="0a4f113b", response="6629fae49393a05397450978507c43ef", opaque="5ccc069c403eb9f0171a9517f4041dbb", algorithm=MD5',
                [
                    'class'      => DigestCredential::class,
                    'properties' => [
                        'username'  => 'Mufasa',
                        'realm'     => 'testrealm@host.com',
                        'nonce'     => 'dcd98b7102dd2f0e8b11d0f600bfb0c093',
                        'uri'       => '/dir/index.html',
                        'response'  => '6629fae49393a05397450978507c43ef',
                        'qop'       => 'auth',
                        'nc'        => '0000000000000001',
                        'cnonce'    => '0a4f113b',
                        'opaque'    => '5ccc069c403eb9f0171a9517f4041dbb',
                        'algorithm' => 'MD5',
                    ],
                ],
            ],
            'valid aws' => [
                'AWS4-HMAC-SHA256 Credential=AKIDEXAMPLE/20150830/us-east-1/service/aws4_request, SignedHeaders=content-type;host;x-amz-date, Signature=5d672d79c15b13162d9279b0855cfba6789a8edb4c82c400e06b5924a6f2b5d7',
                [
                    'class'      => AwsCredential::class,
                    'properties' => [
                        'algorithm'       => Authorization::Aws->value,
                        'credentialScope' => 'AKIDEXAMPLE/20150830/us-east-1/service/aws4_request',
                        'signedHeaders'   => 'content-type;host;x-amz-date',
                        'signature'       => '5d672d79c15b13162d9279b0855cfba6789a8edb4c82c400e06b5924a6f2b5d7',
                    ],
                ],
            ],
            'empty header'                             => ['', null],
            'invalid scheme'                           => ['Unknown scheme', null],
            'malformed basic: no credentials'          => ['Basic', null],
            'malformed basic: invalid base64'          => ['Basic invalid-base64', null],
            'malformed basic: no colon'                => ['Basic dXNlcg==', null], // "user"
            'malformed digest: missing required param' => [
                'Digest username="Mufasa", realm="testrealm@host.com", nonce="dcd98b7102dd2f0e8b11d0f600bfb0c093", qop=auth, nc=0000000000000001, cnonce="0a4f113b", response="6629fae49393a05397450978507c43ef", opaque="5ccc069c403eb9f0171a9517f4041dbb", algorithm=MD5',
                null,
            ],
            'malformed aws: missing required param' => [
                'AWS4-HMAC-SHA256 Credential=AKIDEXAMPLE/20150830/us-east-1/service/aws4_request, SignedHeaders=content-type;host;x-amz-date, MissingSignature=5d672d79c15b13162d9279b0855cfba6789a8edb4c82c400e06b5924a6f2b5d7',
                null,
            ],
            'malformed aws: invalid part format' => [
                'AWS4-HMAC-SHA256 Credential=AKIDEXAMPLE/20150830/us-east-1/service/aws4_request, SignedHeaders=content-type;host;x-amz-date, Signature=invalid-format-no-equals',
                null,
            ],
            'malformed aws: invalid part regex match' => [
                'AWS4-HMAC-SHA256 Credential=AKIDEXAMPLE/20150830/us-east-1/service/aws4_request, SignedHeaders=content-type;host;x-amz-date, Signature=invalid,format',
                null,
            ],
            'malformed digest: invalid part format' => [
                'Digest username="Mufasa", realm="testrealm@host.com", nonce="dcd98b7102dd2f0e8b11d0f600bfb0c093", uri="/dir/index.html", qop=auth, nc=0000000000000001, cnonce="0a4f113b", response="6629fae49393a05397450978507c43ef", opaque="5ccc069c403eb9f0171a9517f4041dbb", algorithm=MD5, invalid_part',
                null,
            ],
        ];
    }

    public static function providerFromHeaderCollection(): array
    {
        return [
            'header present (lowercase)' => [
                ['authorization' => 'Basic dXNlcjpwYXNz'],
                [
                    'class'      => BasicCredential::class,
                    'properties' => ['username' => 'user', 'password' => 'pass'],
                ],
            ],
            'header present (uppercase)' => [
                ['Authorization' => 'Bearer my-secret-token'],
                [
                    'class'      => BearerCredential::class,
                    'properties' => ['token' => 'my-secret-token'],
                ],
            ],
            'header present (mixed case)' => [
                ['aUtHoRiZaTiOn' => 'ApiKey my-key'],
                [
                    'class'      => ApiKeyCredential::class,
                    'properties' => ['key' => 'my-key'],
                ],
            ],
            'header present (array value)' => [
                ['Authorization' => ['Bearer my-secret-token', 'another-token']],
                [
                    'class'      => BearerCredential::class,
                    'properties' => ['token' => 'my-secret-token'],
                ],
            ],
            'header missing' => [
                ['x-custom-header' => 'value'],
                null,
            ],
            'empty header collection' => [
                [],
                null,
            ],
            'empty authorization header' => [
                ['Authorization' => ''],
                null,
            ],
            'malformed authorization header' => [
                ['Authorization' => 'Basic'],
                null,
            ],
        ];
    }

    public static function providerFromRequest(): array
    {
        return [
            'header present (lowercase)' => [
                ['authorization' => ['Basic dXNlcjpwYXNz']],
                [
                    'class'      => BasicCredential::class,
                    'properties' => ['username' => 'user', 'password' => 'pass'],
                ],
            ],
            'header present (uppercase)' => [
                ['Authorization' => ['Bearer my-secret-token']],
                [
                    'class'      => BearerCredential::class,
                    'properties' => ['token' => 'my-secret-token'],
                ],
            ],
            'header present (mixed case)' => [
                ['aUtHoRiZaTiOn' => ['ApiKey my-key']],
                [
                    'class'      => ApiKeyCredential::class,
                    'properties' => ['key' => 'my-key'],
                ],
            ],
            'header present (multiple values)' => [
                ['Authorization' => ['Bearer my-secret-token', 'another-token']],
                [
                    'class'      => BearerCredential::class,
                    'properties' => ['token' => 'my-secret-token'],
                ],
            ],
            'header missing' => [
                ['x-custom-header' => ['value']],
                null,
            ],
            'empty header collection' => [
                [],
                null,
            ],
            'empty authorization header' => [
                ['Authorization' => ['']],
                null,
            ],
            'malformed authorization header' => [
                ['Authorization' => ['Basic']],
                null,
            ],
        ];
    }
}
