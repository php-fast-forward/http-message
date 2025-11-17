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

namespace FastForward\Http\Message\Header;

use FastForward\Http\Message\Header\Authorization\ApiKeyCredential;
use FastForward\Http\Message\Header\Authorization\AuthorizationCredential;
use FastForward\Http\Message\Header\Authorization\AwsCredential;
use FastForward\Http\Message\Header\Authorization\BasicCredential;
use FastForward\Http\Message\Header\Authorization\BearerCredential;
use FastForward\Http\Message\Header\Authorization\DigestCredential;
use Psr\Http\Message\RequestInterface;

/**
 * Enum Authorization.
 *
 * Represents supported HTTP `Authorization` header authentication schemes and
 * provides helpers to parse raw header values into structured credential
 * objects.
 *
 * The `Authorization` header is used to authenticate a user agent with a
 * server, as defined primarily in RFC 7235 and scheme-specific RFCs. This
 * utility enum MUST be used in a case-sensitive manner for its enum values
 * but MUST treat incoming header names and schemes according to the
 * specification of each scheme. Callers SHOULD use the parsing helpers to
 * centralize and normalize authentication handling.
 */
enum Authorization: string
{
    /**
     * A common, non-standard scheme for API key authentication.
     *
     * This scheme is not defined by an RFC and MAY vary between APIs.
     * Implementations using this scheme SHOULD document how the key is
     * generated, scoped, and validated.
     */
    case ApiKey = 'ApiKey';

    /**
     * Basic authentication scheme using Base64-encoded "username:password".
     *
     * Credentials are transmitted in plaintext (after Base64 decoding) and
     * therefore MUST only be used over secure transports such as HTTPS.
     *
     * @see https://datatracker.ietf.org/doc/html/rfc7617
     */
    case Basic = 'Basic';

    /**
     * Bearer token authentication scheme.
     *
     * Commonly used with OAuth 2.0 access tokens and JWTs. Bearer tokens
     * MUST be treated as opaque secrets; any party in possession of a valid
     * token MAY use it to obtain access.
     *
     * @see https://datatracker.ietf.org/doc/html/rfc6750
     */
    case Bearer = 'Bearer';

    /**
     * Digest access authentication scheme.
     *
     * Uses a challenge-response mechanism to avoid sending passwords in
     * cleartext. Implementations SHOULD fully follow the RFC requirements
     * to avoid interoperability and security issues.
     *
     * @see https://datatracker.ietf.org/doc/html/rfc7616
     */
    case Digest = 'Digest';

    /**
     * Amazon Web Services Signature Version 4 scheme.
     *
     * Used to authenticate requests to AWS services. The credential
     * components MUST be constructed according to the AWS Signature Version 4
     * process, or validation will fail on the server side.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/signing-requests-v4.html
     */
    case Aws = 'AWS4-HMAC-SHA256';

    /**
     * Parses a raw Authorization header string into a structured credential object.
     *
     * This method MUST:
     * - Split the header into an authentication scheme and a credentials part.
     * - Resolve the scheme to a supported enum value.
     * - Delegate to the appropriate scheme-specific parser.
     *
     * If the header is empty, malformed, or uses an unsupported scheme,
     * this method MUST return null. Callers SHOULD treat a null result as
     * an authentication parsing failure.
     *
     * @param string $header the raw value of the `Authorization` header
     *
     * @return null|AuthorizationCredential a credential object on successful parsing, or null on failure
     */
    public static function parse(string $header): ?AuthorizationCredential
    {
        if ('' === $header) {
            return null;
        }

        $parts = explode(' ', $header, 2);
        if (2 !== \count($parts)) {
            return null;
        }

        [$scheme, $credentials] = $parts;

        $authScheme = self::tryFrom($scheme);
        if (null === $authScheme) {
            return null;
        }

        return match ($authScheme) {
            self::ApiKey => self::parseApiKey($credentials),
            self::Basic  => self::parseBasic($credentials),
            self::Bearer => self::parseBearer($credentials),
            self::Digest => self::parseDigest($credentials),
            self::Aws    => self::parseAws($credentials),
        };
    }

    /**
     * Extracts and parses the Authorization header from a collection of headers.
     *
     * This method MUST treat header names case-insensitively and SHALL use
     * the first `Authorization` value if multiple values are provided. If the
     * header is missing or cannot be parsed successfully, it MUST return null.
     *
     * @param array<string, string|string[]> $headers an associative array of HTTP headers
     *
     * @return null|AuthorizationCredential a parsed credential object or null if not present or invalid
     */
    public static function fromHeaderCollection(array $headers): ?AuthorizationCredential
    {
        $normalizedHeaders = array_change_key_case($headers, CASE_LOWER);

        if (!isset($normalizedHeaders['authorization'])) {
            return null;
        }

        $authHeaderValue = $normalizedHeaders['authorization'];

        if (\is_array($authHeaderValue)) {
            $authHeaderValue = $authHeaderValue[0];
        }

        return self::parse($authHeaderValue);
    }

    /**
     * Extracts and parses the Authorization header from a PSR-7 request.
     *
     * This method SHALL delegate to {@see Authorization::fromHeaderCollection()}
     * using the request's header collection. It MUST NOT modify the request.
     *
     * @param RequestInterface $request the PSR-7 request instance
     *
     * @return null|AuthorizationCredential a parsed credential object or null if not present or invalid
     */
    public static function fromRequest(RequestInterface $request): ?AuthorizationCredential
    {
        return self::fromHeaderCollection($request->getHeaders());
    }

    /**
     * Parses credentials for the ApiKey authentication scheme.
     *
     * The complete credential string MUST be treated as the API key. No
     * additional structure is assumed or validated here; callers MAY apply
     * further validation according to application rules.
     *
     * @param string $credentials the raw credentials portion of the header
     *
     * @return ApiKeyCredential the parsed API key credential object
     */
    private static function parseApiKey(string $credentials): ApiKeyCredential
    {
        return new ApiKeyCredential($credentials);
    }

    /**
     * Parses credentials for the Basic authentication scheme.
     *
     * This method MUST:
     * - Base64-decode the credentials.
     * - Split the decoded string into `username:password`.
     *
     * If decoding fails or the decoded value does not contain exactly one
     * colon separator, this method MUST return null.
     *
     * @param string $credentials the Base64-encoded "username:password" string
     *
     * @return null|BasicCredential the parsed Basic credential, or null on failure
     */
    private static function parseBasic(string $credentials): ?BasicCredential
    {
        $decoded = base64_decode($credentials, true);
        if (false === $decoded) {
            return null;
        }

        $parts = explode(':', $decoded, 2);
        if (2 !== \count($parts)) {
            return null;
        }

        [$username, $password] = $parts;

        return new BasicCredential($username, $password);
    }

    /**
     * Parses credentials for the Bearer authentication scheme.
     *
     * The credentials MUST be treated as an opaque bearer token. This method
     * SHALL NOT attempt to validate or inspect the token contents.
     *
     * @param string $credentials the bearer token string
     *
     * @return BearerCredential the parsed Bearer credential object
     */
    private static function parseBearer(string $credentials): BearerCredential
    {
        return new BearerCredential($credentials);
    }

    /**
     * Parses credentials for the Digest authentication scheme.
     *
     * This method MUST parse comma-separated key=value pairs according to
     * RFC 7616. Values MAY be quoted or unquoted. If any part is malformed
     * or required parameters are missing, it MUST return null.
     *
     * Required parameters:
     * - username
     * - realm
     * - nonce
     * - uri
     * - response
     * - qop
     * - nc
     * - cnonce
     *
     * Optional parameters such as `opaque` and `algorithm` SHALL be included
     * in the credential object when present.
     *
     * @param string $credentials the raw credentials portion of the header
     *
     * @return null|DigestCredential the parsed Digest credential object, or null on failure
     */
    private static function parseDigest(string $credentials): ?DigestCredential
    {
        $params = [];
        $parts  = explode(',', $credentials);

        foreach ($parts as $part) {
            $part = mb_trim($part);

            $pattern = '/^(?<key>[a-zA-Z0-9_-]+)=(?<value>"[^"]*"|[^"]*)$/i';

            if (!preg_match($pattern, $part, $match)) {
                return null;
            }

            $key          = mb_strtolower($match['key']);
            $value        = mb_trim($match['value'], '"');
            $params[$key] = $value;
        }

        $required = ['username', 'realm', 'nonce', 'uri', 'response', 'qop', 'nc', 'cnonce'];
        foreach ($required as $key) {
            if (!isset($params[$key])) {
                return null;
            }
        }

        return new DigestCredential(
            username: $params['username'],
            realm: $params['realm'],
            nonce: $params['nonce'],
            uri: $params['uri'],
            response: $params['response'],
            qop: $params['qop'],
            nc: $params['nc'],
            cnonce: $params['cnonce'],
            opaque: $params['opaque'] ?? null,
            algorithm: $params['algorithm'] ?? null,
        );
    }

    /**
     * Parses credentials for the AWS Signature Version 4 authentication scheme.
     *
     * This method MUST parse comma-separated key=value pairs and verify that
     * the mandatory parameters `Credential`, `SignedHeaders`, and `Signature`
     * are present. The `Signature` value MUST be a 64-character hexadecimal
     * string. If parsing or validation fails, it MUST return null.
     *
     * The `Credential` parameter contains the full credential scope in the form
     * `AccessKeyId/Date/Region/Service/aws4_request`, which SHALL be stored
     * as-is for downstream processing.
     *
     * @param string $credentials the raw credentials portion of the header
     *
     * @return null|AwsCredential the parsed AWS credential object, or null on failure
     */
    private static function parseAws(string $credentials): ?AwsCredential
    {
        $params = [];
        $parts  = explode(',', $credentials);

        foreach ($parts as $part) {
            $part = mb_trim($part);

            $pattern = '/^(?<key>[a-zA-Z0-9_-]+)=(?<value>[^, ]+)$/';

            if (!preg_match($pattern, $part, $match)) {
                return null;
            }

            $key          = mb_trim($match['key']);
            $value        = mb_trim($match['value']);
            $params[$key] = $value;
        }

        $required = ['Credential', 'SignedHeaders', 'Signature'];
        foreach ($required as $key) {
            if (!isset($params[$key])) {
                return null;
            }
        }

        if (!preg_match('/^[0-9a-fA-F]{64}$/', $params['Signature'])) {
            return null;
        }

        return new AwsCredential(
            algorithm: self::Aws->value,
            credentialScope: $params['Credential'],
            signedHeaders: $params['SignedHeaders'],
            signature: $params['Signature'],
        );
    }
}
