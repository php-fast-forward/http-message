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
use Psr\Http\Message\UriInterface;

/**
 * Class RedirectResponse.
 *
 * Represents an HTTP redirect response with customizable status codes for temporary or permanent redirects.
 * This class MUST be used for generating HTTP responses that instruct clients to navigate to a different location,
 * by automatically setting the 'Location' header.
 *
 * @package FastForward\Http\Message
 */
final class RedirectResponse extends Response
{
    /**
     * Constructs a new RedirectResponse instance.
     *
     * This constructor SHALL set the 'Location' header and apply the appropriate HTTP status code
     * for temporary (302 Found) or permanent (301 Moved Permanently) redirects.
     *
     * @param string|UriInterface            $uri       The target URI for redirection. MUST be absolute or relative according to context.
     * @param bool                           $permanent if true, the response status will be 301 (permanent redirect); otherwise, 302 (temporary redirect)
     * @param array<string, string|string[]> $headers   optional additional headers to include in the response
     */
    public function __construct(string|UriInterface $uri, bool $permanent = false, array $headers = [])
    {
        $headers['Location'] = (string) $uri;
        $status              = $permanent ? StatusCode::MovedPermanently : StatusCode::Found;

        parent::__construct(
            status: $status->value,
            headers: $headers,
            reason: $status->getReasonPhrase(),
        );
    }
}
