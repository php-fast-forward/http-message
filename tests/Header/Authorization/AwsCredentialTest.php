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

use FastForward\Http\Message\Header\Authorization;
use FastForward\Http\Message\Header\Authorization\AwsCredential;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(AwsCredential::class)]
#[UsesClass(Authorization::class)]
final class AwsCredentialTest extends TestCase
{
    public function testConstructorAndProperties(): void
    {
        $credential = new AwsCredential(
            algorithm: Authorization::Aws->value,
            credentialScope: 'AKIDEXAMPLE/20150830/us-east-1/service/aws4_request',
            signedHeaders: 'content-type;host;x-amz-date',
            signature: '5d672d79c15b13162d9279b0855cfba6789a8edb4c82c400e06b5924a6f2b5d7',
        );

        self::assertSame('AWS4-HMAC-SHA256', $credential->algorithm);
        self::assertSame('AKIDEXAMPLE/20150830/us-east-1/service/aws4_request', $credential->credentialScope);
        self::assertSame('content-type;host;x-amz-date', $credential->signedHeaders);
        self::assertSame('5d672d79c15b13162d9279b0855cfba6789a8edb4c82c400e06b5924a6f2b5d7', $credential->signature);
    }
}
