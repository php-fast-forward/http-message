<?php

/**
 * This file is part of php-fast-forward/http-message.
 *
 * This source file is subject to the license bundled
 * with this source code in the file LICENSE.
 *
 * @see      https://github.com/php-fast-forward/http-message
 *
 * @copyright Copyright (c) 2025 Felipe Sayão Lobato Abreu <github@mentordosnerds.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

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
 * Enum TransferEncoding.
 *
 * Represents the HTTP `Transfer-Encoding` header values.
 *
 * Transfer-Encoding is a hop-by-hop header, meaning it applies only to a
 * single transport-level connection and MUST NOT be stored or reused by
 * caches for subsequent requests or responses. The listed values indicate
 * which transfer codings have been applied to the message body in order to
 * safely move it between intermediaries.
 *
 * Implementations using this enum SHOULD ensure that the semantics
 * of chunked transfer-coding and any compression codings are respected
 * according to the relevant HTTP specifications.
 *
 * @see https://datatracker.ietf.org/doc/html/rfc7230#section-3.3.1
 * @see https://datatracker.ietf.org/doc/html/rfc7230#section-4.1
 */
enum TransferEncoding: string
{
    /**
     * Indicates that the message body is sent as a series of chunks.
     *
     * Chunked transfer-coding is the only transfer-coding that is mandatory
     * for HTTP/1.1 compliance and MUST be understood by all HTTP/1.1 agents
     * that advertise support for `Transfer-Encoding`.
     *
     * When present, `chunked` MUST be the final transfer-coding applied to
     * the payload. It allows the sender to stream content without knowing
     * the final length in advance.
     */
    case Chunked = 'chunked';

    /**
     * A transfer-coding using the Lempel-Ziv-Welch (LZW) algorithm.
     *
     * This coding derives from the historic UNIX `compress` format. It is
     * largely obsolete in modern HTTP and SHOULD NOT be used for new
     * deployments. Many clients no longer support it due to past patent
     * concerns and limited practical usage.
     */
    case Compress = 'compress';

    /**
     * A transfer-coding using the zlib structure with the DEFLATE
     * compression algorithm.
     *
     * This coding uses the zlib framing (RFC 1950) combined with the DEFLATE
     * algorithm (RFC 1951). Implementations MUST take care not to confuse
     * this with a “raw deflate” stream without zlib framing.
     */
    case Deflate = 'deflate';

    /**
     * A transfer-coding using the Lempel-Ziv coding (LZ77) with a 32-bit CRC.
     *
     * This corresponds to the traditional `gzip` format produced by the
     * UNIX `gzip` program. Some legacy systems MAY also accept `x-gzip`,
     * but as a transfer-coding value, `gzip` itself SHOULD be preferred.
     */
    case Gzip = 'gzip';

    /**
     * Determines whether the given `Transfer-Encoding` header indicates that
     * chunked transfer-coding has been applied.
     *
     * The `chunked` token MAY appear as one of several comma-separated values.
     * This method performs a case-insensitive search for its presence. While
     * RFC 7230 specifies that `chunked` MUST be the final transfer-coding
     * when present, this method deliberately checks only for its existence to
     * provide a more robust, tolerant interpretation of real-world headers.
     *
     * Callers SHOULD use this method before attempting to parse a message
     * body as chunked data.
     *
     * @param string $transferEncodingHeader the raw `Transfer-Encoding` header value
     *
     * @return bool true if `chunked` is present (case-insensitive), false otherwise
     */
    public static function isChunked(string $transferEncodingHeader): bool
    {
        if ('' === $transferEncodingHeader) {
            return false;
        }

        $codings = array_map('\mb_trim', explode(',', mb_strtolower($transferEncodingHeader)));

        return \in_array(self::Chunked->value, $codings, true);
    }
}
