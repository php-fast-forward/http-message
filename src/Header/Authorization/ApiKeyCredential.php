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
 * Class ApiKeyCredential.
 *
 * Represents the credential structure for API Key–based authentication.
 * Implementations using this credential MUST treat the API key as an opaque
 * secret. The value MUST NOT be logged, exposed, or transmitted to
 * unauthorized parties, as possession of the key typically grants full
 * authorization to the associated account or resource.
 *
 * This class SHALL be returned by the {@see Authorization::ApiKey} parser
 * when the `Authorization` header contains a valid API key value. The key
 * MAY represent either a static key, a signed token, or any user-defined
 * string depending on the server's authentication strategy.
 */
final class ApiKeyCredential implements AuthorizationCredential
{
    /**
     * Creates a new API Key credential instance.
     *
     * The provided key MUST be stored exactly as received and MUST NOT be
     * modified or normalized internally. Any validation, expiration checks,
     * or transformation logic MUST be performed by the caller or the
     * authentication subsystem responsible for interpreting API keys.
     *
     * @param string $key the raw API key provided by the client
     */
    public function __construct(
        #[\SensitiveParameter]
        public readonly string $key,
    ) {}
}
