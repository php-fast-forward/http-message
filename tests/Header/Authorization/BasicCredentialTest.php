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

namespace FastForward\Http\Message\Tests\Header\Authorization;

use FastForward\Http\Message\Header\Authorization\BasicCredential;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(BasicCredential::class)]
final class BasicCredentialTest extends TestCase
{
    /**
     * @return void
     */
    #[Test]
    public function constructWithUsernameAndPasswordWillReturnCredentialWithPropertiesSet(): void
    {
        $username   = 'testuser';
        $password   = 'testpass';
        $credential = new BasicCredential($username, $password);

        self::assertSame($username, $credential->username);
        self::assertSame($password, $credential->password);
    }
}
