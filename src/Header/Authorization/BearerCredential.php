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

namespace FastForward\Http\Message\Header\Authorization;

use SensitiveParameter;

/**
 * Class BearerCredential.
 *
 * Represents the parsed credential for HTTP Bearer Token Authentication.
 * Bearer tokens MUST be treated as opaque secrets that grant access to the
 * associated protected resource. Any party in possession of the token MAY
 * use it, therefore implementations MUST ensure the token is never exposed
 * in logs, stack traces, debug output, or error messages.
 *
 * This credential SHALL be produced by the Bearer authentication parser in
 * {@see FastForward\Http\Message\Header\Authorization::parse()} when a valid
 * Bearer token is provided by the client.
 */
final readonly class BearerCredential implements AuthorizationCredential
{
    /**
     * Creates a new Bearer token credential instance.
     *
     * The token parameter is marked with `#[\SensitiveParameter]` because it
     * MUST be handled as a private security secret; leaking its value may
     * allow unauthorized access to the protected system.
     *
     * @param string $token the opaque bearer token provided by the client
     */
    public function __construct(
        #[SensitiveParameter]
        public string $token,
    ) {}
}
