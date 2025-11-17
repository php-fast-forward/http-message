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
 * Enum ContentType.
 *
 * Represents a comprehensive set of HTTP Content-Type header values.
 * Each enum case describes a MIME type that MAY be used when constructing or
 * parsing HTTP messages. Implementations interacting with this enum SHOULD
 * ensure appropriate handling based on RFC 2119 requirement levels.
 *
 * This enum MUST be used when normalizing, validating, or comparing Content-Type
 * header values in a strict and type-safe manner. It SHALL provide helper
 * methods for extracting metadata, ensuring consistent behavior across HTTP
 * message handling.
 */
enum ContentType: string
{
    // Text Types
    case TextPlain = 'text/plain';
    case TextHtml  = 'text/html';
    case TextCss   = 'text/css';
    case TextCsv   = 'text/csv';
    case TextXml   = 'text/xml';

    // Application Types
    case ApplicationJson           = 'application/json';
    case ApplicationXml            = 'application/xml';
    case ApplicationFormUrlencoded = 'application/x-www-form-urlencoded';
    case ApplicationPdf            = 'application/pdf';
    case ApplicationJavascript     = 'application/javascript';
    case ApplicationOctetStream    = 'application/octet-stream';

    // Multipart Types
    case MultipartFormData = 'multipart/form-data';

    // Image Types
    case ImageJpeg = 'image/jpeg';
    case ImagePng  = 'image/png';
    case ImageGif  = 'image/gif';
    case ImageSvg  = 'image/svg+xml';

    /**
     * Creates a ContentType instance from a full Content-Type header string.
     *
     * This method SHALL parse header values that include parameters such as
     * charsets or boundary markers. Only the primary MIME type SHALL be used
     * for determining the enum case. If the base MIME type does not match any
     * known ContentType, this method MUST return null.
     *
     * Example:
     *   "application/json; charset=utf-8" → ContentType::ApplicationJson
     *
     * @param string $header the full Content-Type header string
     *
     * @return null|self the derived ContentType case or null if unsupported
     */
    public static function fromHeaderString(string $header): ?self
    {
        $baseType = strtok($header, ';');
        if (false === $baseType) {
            return null;
        }

        return self::tryFrom($baseType);
    }

    /**
     * Extracts the charset parameter from a Content-Type header string.
     *
     * This method SHOULD be used when charset negotiation or validation is
     * required. If no charset is present, this method MUST return null. The
     * extracted charset value SHALL be trimmed of surrounding whitespace.
     *
     * Example:
     *   "application/json; charset=utf-8" → "utf-8"
     *
     * @param string $contentTypeHeader the full Content-Type header value
     *
     * @return null|string the charset value or null if absent
     */
    public static function getCharset(string $contentTypeHeader): ?string
    {
        if (preg_match('/charset=([^;]+)/i', $contentTypeHeader, $matches)) {
            return mb_trim($matches[1]);
        }

        return null;
    }

    /**
     * Returns the Content-Type header value with an appended charset parameter.
     *
     * The returned string MUST follow the standard "type/subtype; charset=X"
     * format. Implementations SHOULD ensure the provided charset is valid
     * according to application requirements.
     *
     * @param string $charset the charset to append to the Content-Type
     *
     * @return string the resulting Content-Type header value
     */
    public function withCharset(string $charset): string
    {
        return $this->value . '; charset=' . $charset;
    }

    /**
     * Determines whether the content type represents JSON data.
     *
     * This method SHALL evaluate strictly via enum identity comparison. It MUST
     * return true only for application/json.
     *
     * @return bool true if JSON type, false otherwise
     */
    public function isJson(): bool
    {
        return self::ApplicationJson === $this;
    }

    /**
     * Determines whether the content type represents XML data.
     *
     * This method SHALL consider both application/xml and text/xml as valid XML
     * content types. It MUST return true for either enumeration case.
     *
     * @return bool true if XML type, false otherwise
     */
    public function isXml(): bool
    {
        return self::ApplicationXml === $this || self::TextXml === $this;
    }

    /**
     * Determines whether the content type represents text-based content.
     *
     * Any MIME type beginning with "text/" SHALL be treated as text content.
     * This method MUST use string prefix evaluation according to the enum value.
     *
     * @return bool true if text-based type, false otherwise
     */
    public function isText(): bool
    {
        return str_starts_with($this->value, 'text/');
    }

    /**
     * Determines whether the content type represents multipart data.
     *
     * Multipart types are typically used for form uploads and MUST begin with
     * "multipart/". This method SHALL match MIME type prefixes accordingly.
     *
     * @return bool true if multipart type, false otherwise
     */
    public function isMultipart(): bool
    {
        return str_starts_with($this->value, 'multipart/');
    }
}
