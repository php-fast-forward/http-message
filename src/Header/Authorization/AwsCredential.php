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
 * Class AwsCredential.
 *
 * Represents the structured credential for AWS Signature Version 4
 * authentication. This credential is extracted from an `Authorization`
 * header beginning with the scheme `AWS4-HMAC-SHA256`.
 *
 * AWS Signature Version 4 requires an HMAC-based signing process in which the
 * client computes a derived signing key using its AWS secret access key,
 * the request date, region, service name, and a fixed terminator string
 * (`aws4_request`). The client then signs a canonical representation of the
 * HTTP request. The server reconstructs this process and validates the
 * signature to authenticate the request.
 *
 * Implementations using this class MUST treat all contained values as
 * immutable authentication parameters. These values MUST NOT be modified
 * internally, and callers SHOULD validate them strictly according to AWS
 * signing rules. The `signature` value MUST be treated as opaque binary
 * content encoded in hexadecimal; possession of a valid signature MAY allow
 * unauthorized access if mishandled.
 *
 * Each property corresponds directly to fields parsed from the
 * `Authorization` header:
 *
 * - **algorithm**: The signing algorithm identifier. For SigV4 this MUST be
 *   `"AWS4-HMAC-SHA256"`.
 * - **credentialScope**: The hierarchical credential scope string in the form:
 *   `AccessKeyId/Date/Region/Service/aws4_request`.
 * - **signedHeaders**: A semicolon-delimited list of header names included
 *   during canonicalization. The server MUST reconstruct these headers in
 *   exactly the same order for signature verification.
 * - **signature**: A 64-character hexadecimal string representing the
 *   computed request signature.
 */
final class AwsCredential implements AuthorizationCredential
{
    /**
     * Creates a representation of the SigV4 credential parameters extracted
     * from an Authorization header.
     *
     * All values passed to this constructor MUST come directly from the parsed
     * header and MUST NOT be transformed semantically. Any additional
     * normalization required for validation (e.g., canonical header
     * reconstruction) MUST be performed by the caller or authentication
     * subsystem.
     *
     * @param string $algorithm       the SigV4 signing algorithm identifier
     * @param string $credentialScope the credential scope string
     *                                (`AccessKeyId/Date/Region/Service/aws4_request`)
     * @param string $signedHeaders   a semicolon-separated list of signed headers
     * @param string $signature       a 64-character hex-encoded signature
     */
    public function __construct(
        public readonly string $algorithm,
        #[\SensitiveParameter]
        public readonly string $credentialScope,
        public readonly string $signedHeaders,
        #[\SensitiveParameter]
        public readonly string $signature,
    ) {}
}
