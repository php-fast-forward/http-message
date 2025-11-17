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
 * Class DigestCredential.
 *
 * Represents the parsed credential set for HTTP Digest Authentication
 * (RFC 7616). Digest Authentication uses a challenge–response mechanism
 * that avoids transmitting passwords in plaintext, but several fields
 * remain highly sensitive because they directly participate in the
 * hash computation or reflect secret client state.
 *
 * Implementations handling this class MUST treat the `response`, `cnonce`,
 * `nonce`, and `nc` parameters as sensitive information. These values
 * MUST NOT be logged, exposed, or included in error messages. While the
 * original password is not transmitted, the combination of these fields
 * MAY allow offline credential recovery if leaked.
 *
 * The `username`, `realm`, and `uri` fields generally do not contain
 * secret information, though they SHOULD still be handled carefully.
 */
final class DigestCredential implements AuthorizationCredential
{
    /**
     * Creates a Digest Authentication credential.
     *
     * Sensitive parameters are annotated with `#[\SensitiveParameter]` to
     * ensure that debugging output and exception traces do not reveal
     * confidential values used in the authentication hash.
     *
     * @param string      $username  the username supplied by the client
     * @param string      $realm     the challenge-provided realm value
     * @param string      $nonce     the server-generated nonce used in hashing
     * @param string      $uri       the requested URI
     * @param string      $response  the computed digest response hash
     * @param string      $qop       the quality of protection value
     * @param string      $nc        the nonce count, incremented by the client
     * @param string      $cnonce    the client-generated nonce
     * @param null|string $opaque    optional server-provided opaque value
     * @param null|string $algorithm algorithm identifier, usually "MD5"
     */
    public function __construct(
        public readonly string $username,
        public readonly string $realm,
        #[\SensitiveParameter]
        public readonly string $nonce,
        public readonly string $uri,
        #[\SensitiveParameter]
        public readonly string $response,
        public readonly string $qop,
        #[\SensitiveParameter]
        public readonly string $nc,
        #[\SensitiveParameter]
        public readonly string $cnonce,
        public readonly ?string $opaque = null,
        public readonly ?string $algorithm = null,
    ) {}
}
