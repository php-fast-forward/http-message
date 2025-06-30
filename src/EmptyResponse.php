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

/**
 * Class EmptyResponse.
 *
 * Represents an HTTP 204 No Content response.
 *
 * This class MUST be used when generating responses that intentionally contain no body content,
 * in compliance with RFC 9110. It automatically sets the HTTP status code to 204 (No Content)
 * and applies an optional set of headers.
 *
 * @package FastForward\Http\Message
 */
final class EmptyResponse extends Response
{
    /**
     * Constructs a new EmptyResponse instance with optional headers.
     *
     * This constructor SHALL initialize the response with HTTP status 204 and no body content.
     * The 'reason' phrase for status 204 is automatically included based on StatusCode enumeration.
     *
     * @param array<string, string|string[]> $headers optional headers to include in the response
     */
    public function __construct(array $headers = [])
    {
        parent::__construct(
            status: StatusCode::NoContent->value,
            headers: $headers,
            reason: StatusCode::NoContent->getReasonPhrase(),
        );
    }
}
