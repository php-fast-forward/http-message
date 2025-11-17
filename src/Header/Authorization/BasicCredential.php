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

namespace FastForward\Http\Message\Header\Authorization;

/**
 * Class BasicCredential.
 *
 * Represents the parsed credential pair for HTTP Basic Authentication.
 * This credential consists of a username and password encoded as
 * `Base64(username:password)` in the `Authorization` header.
 *
 * Implementations handling this class MUST treat the password as a sensitive
 * secret. It MUST NOT be logged, exposed, or transmitted insecurely. The
 * username MAY be considered non-sensitive depending on application rules,
 * but the password MUST always be protected.
 *
 * Instances of this class SHALL be returned by
 * {@see FastForward\Http\Message\Header\Authorization::parse()}
 * when the header contains a valid Basic Authentication value.
 */
final class BasicCredential implements AuthorizationCredential
{
    /**
     * Creates a new Basic Authentication credential.
     *
     * The username and password MUST be extracted exactly as decoded from the
     * HTTP Authorization header. The password parameter is annotated with
     * `#[\SensitiveParameter]` to ensure that stack traces, debugging tools,
     * and error handlers do not accidentally reveal its value.
     *
     * @param string $username the username provided by the client
     * @param string $password the plaintext password provided by the client
     */
    public function __construct(
        public readonly string $username,
        #[\SensitiveParameter]
        public readonly string $password,
    ) {}
}
