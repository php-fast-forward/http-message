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

namespace FastForward\Http\Message;

use Nyholm\Psr7\Response;
use Nyholm\Psr7\Stream;

/**
 * Class HtmlResponse.
 *
 * Represents an HTTP response containing HTML content.
 *
 * This class MUST be used to generate HTTP responses with a `text/html` content type.
 * It automatically sets the 'Content-Type' header, encodes the body using the specified charset,
 * and applies the HTTP 200 (OK) status code by default.
 *
 * @package FastForward\Http\Message
 */
final class HtmlResponse extends Response
{
    /**
     * Constructs a new HtmlResponse instance.
     *
     * This constructor SHALL set the 'Content-Type' header to `text/html` with the specified charset
     * and initialize the response body with the provided HTML content. The response status code
     * will be set to 200 (OK) by default, with the corresponding reason phrase.
     *
     * @param string                         $html    the HTML content to send in the response body
     * @param string                         $charset The character encoding to declare in the 'Content-Type' header. Defaults to 'utf-8'.
     * @param array<string, string|string[]> $headers optional additional headers to include in the response
     */
    public function __construct(string $html, string $charset = 'utf-8', array $headers = [])
    {
        $headers['Content-Type'] = 'text/html; charset=' . $charset;

        parent::__construct(
            status: StatusCode::Ok->value,
            headers: $headers,
            body: Stream::create($html),
            reason: StatusCode::Ok->getReasonPhrase(),
        );
    }
}
