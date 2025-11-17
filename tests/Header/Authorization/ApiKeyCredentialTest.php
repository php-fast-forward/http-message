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

use FastForward\Http\Message\Header\Authorization\ApiKeyCredential;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(ApiKeyCredential::class)]
final class ApiKeyCredentialTest extends TestCase
{
    public function testConstructorAndProperties(): void
    {
        $key        = 'my-secret-api-key';
        $credential = new ApiKeyCredential($key);

        self::assertSame($key, $credential->key);
    }
}
