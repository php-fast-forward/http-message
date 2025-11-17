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

use FastForward\Http\Message\Header\ContentType;
use Nyholm\Psr7\Response;

/**
 * Class JsonResponse.
 *
 * Provides a JSON-specific HTTP response implementation that complies with PSR-7 Response interfaces.
 * This class MUST be used when returning JSON payloads over HTTP responses.
 * It automatically sets the 'Content-Type' header to 'application/json' with the specified charset.
 *
 * @method PayloadStreamInterface getBody() Retrieves the response body as a JSON stream.
 */
final class JsonResponse extends Response implements PayloadResponseInterface
{
    /**
     * Constructs a new JsonResponse instance with an optional payload and charset.
     *
     * This constructor SHALL initialize the response body with a JsonStream containing the provided payload.
     * The 'Content-Type' header MUST be set to 'application/json' with the specified charset.
     *
     * @param mixed  $payload The JSON-serializable payload to send in the response body. Defaults to an empty array.
     * @param string $charset The character encoding to use in the 'Content-Type' header. Defaults to 'utf-8'.
     */
    public function __construct(
        mixed $payload = [],
        string $charset = 'utf-8',
        array $headers = [],
    ) {
        $headers['Content-Type'] = ContentType::ApplicationJson->withCharset($charset);

        parent::__construct(
            headers: $headers,
            body: new JsonStream($payload),
        );
    }

    /**
     * Retrieves the payload contained in the response body.
     *
     * This method MUST return the same payload provided during construction or via withPayload().
     *
     * @return mixed the decoded JSON payload
     */
    public function getPayload(): mixed
    {
        return $this->getBody()->getPayload();
    }

    /**
     * Returns an instance with the specified payload.
     *
     * This method SHALL return a new instance of the response with the body replaced by a new JsonStream
     * containing the provided payload. The original instance MUST remain unchanged, ensuring immutability
     * as required by PSR-7.
     *
     * @param mixed $payload the new JSON-serializable payload
     *
     * @return self a new JsonResponse instance with the updated payload
     */
    public function withPayload(mixed $payload): self
    {
        return $this->withBody($this->getBody()->withPayload($payload));
    }
}
