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

/**
 * Enum ContentEncoding.
 *
 * Represents common and experimental HTTP Content-Encoding header values.
 * Content-Encoding defines the compression mechanism applied to the HTTP
 * message body. Implementations using this enum MUST follow the semantics
 * defined in RFC 7231, RFC 9110, and the relevant algorithm RFCs.
 *
 * Each encoding describes a specific compression algorithm or an identity
 * transformation. Servers and intermediaries using this enum SHOULD ensure
 * that content negotiation is performed safely and consistently according
 * to client capabilities, honoring q-values and alias mappings.
 */
enum ContentEncoding: string
{
    /**
     * A format using the Lempel-Ziv coding (LZ77) with a 32-bit CRC.
     *
     * The HTTP/1.1 standard states that servers supporting this encoding
     * SHOULD also recognize `"x-gzip"` as an alias for compatibility.
     * Implementations consuming this enum MUST treat both forms as
     * equivalent during content negotiation.
     */
    case Gzip = 'gzip';

    /**
     * A format using the Lempel-Ziv-Welch (LZW) algorithm.
     *
     * Historically derived from the UNIX `compress` program. This encoding
     * is largely obsolete in modern HTTP contexts and SHOULD NOT be used
     * except for legacy interoperation.
     */
    case Compress = 'compress';

    /**
     * A format using the zlib framing structure (RFC 1950) with the
     * DEFLATE compression algorithm (RFC 1951).
     *
     * This encoding MUST NOT be confused with “raw deflate” streams.
     */
    case Deflate = 'deflate';

    /**
     * A format using the Brotli compression algorithm.
     *
     * Defined in RFC 7932, Brotli provides modern general-purpose
     * compression and SHOULD be preferred over older schemes such as gzip
     * when client support is present.
     */
    case Brotli = 'br';

    /**
     * A format using the Zstandard compression algorithm.
     *
     * Defined in RFC 8878, Zstandard (“zstd”) offers high compression
     * ratios and fast decompression. Implementations MAY use dictionary
     * compression where supported by the protocol extension.
     */
    case Zstd = 'zstd';

    /**
     * Indicates the identity function (no compression).
     *
     * The identity encoding MUST be considered acceptable if the client
     * omits an Accept-Encoding header. It MUST NOT apply any compression
     * transformation to the content.
     */
    case Identity = 'identity';

    /**
     * Experimental: A format using the Dictionary-Compressed Brotli algorithm.
     *
     * See the Compression Dictionary Transport specification. This encoding
     * is experimental and MAY NOT be supported by all clients.
     */
    case Dcb = 'dcb';

    /**
     * Experimental: A format using the Dictionary-Compressed Zstandard algorithm.
     *
     * See the Compression Dictionary Transport specification. This encoding
     * is experimental and MAY NOT be supported by all clients.
     */
    case Dcz = 'dcz';

    /**
     * Determines whether a given encoding is acceptable according to an
     * `Accept-Encoding` header value.
     *
     * This method MUST correctly apply HTTP content negotiation rules:
     * - Parse q-values, which MUST determine the client's preference level.
     * - Interpret “q=0” as explicit rejection.
     * - Support wildcards (“*”) as fallback.
     * - Recognize “x-gzip” as an alias for the gzip encoding.
     *
     * If an encoding is not explicitly listed and no wildcard is present,
     * the encoding SHOULD be considered acceptable unless the header
     * exclusively lists explicit rejections.
     *
     * @param self   $encoding             the encoding to evaluate
     * @param string $acceptEncodingHeader the raw `Accept-Encoding` header value
     *
     * @return bool true if the encoding is acceptable according to negotiation rules
     */
    public static function isSupported(self $encoding, string $acceptEncodingHeader): bool
    {
        $preferences = [];
        $pattern     = '/(?<name>[a-z*-]+)(?:;\s*q=(?<q>[0-9.]+))?/i';

        if (\preg_match_all($pattern, $acceptEncodingHeader, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $name                               = \mb_trim($match['name']);
                $q                                  = isset($match['q']) && '' !== $match['q'] ? (float) $match['q'] : 1.0;
                $preferences[\mb_strtolower($name)] = $q;
            }
        }

        $encodingName = \mb_strtolower($encoding->value);
        $aliases      = self::getAliases($encoding);

        $checkNames = [$encodingName, ...$aliases];

        foreach ($checkNames as $name) {
            if (isset($preferences[$name])) {
                return $preferences[$name] > 0.0;
            }
        }

        if (isset($preferences['*'])) {
            return $preferences['*'] > 0.0;
        }

        return true;
    }

    /**
     * Returns known alias names for a given encoding.
     *
     * Implementations MUST treat aliases as equivalent when performing
     * content negotiation. Currently only gzip uses an alias (“x-gzip”),
     * but future extensions MAY introduce additional aliases.
     *
     * @param self $encoding the encoding whose aliases will be returned
     *
     * @return string[] a list of lowercase alias identifiers
     */
    private static function getAliases(self $encoding): array
    {
        return match ($encoding) {
            self::Gzip => ['x-gzip'],
            default    => [],
        };
    }
}
