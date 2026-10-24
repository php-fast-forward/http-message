<?php

declare(strict_types=1);

/**
 * This file is part of php-fast-forward/http-message.
 *
 * This source file is subject to the license bundled
 * with this source code in the file LICENSE.
 *
 * @copyright Copyright (c) 2025-2026 Felipe Sayão Lobato Abreu <github@mentordosnerds.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 *
 * @see       https://github.com/php-fast-forward/http-message
 * @see       https://github.com/php-fast-forward
 * @see       https://datatracker.ietf.org/doc/html/rfc2119
 */

namespace FastForward\Http\Message;

use FastForward\Http\Message\Header\ContentType;
use JsonException;
use Nyholm\Psr7\Response;
use RuntimeException;

/**
 * Class JsonResponse.
 *
 * Provides a JSON-specific HTTP response implementation that complies with PSR-7 Response interfaces.
 * This class MUST be used when returning JSON payloads over HTTP responses.
 * It automatically sets the 'Content-Type' header to 'application/json' with the specified charset.
 *
 * A body replaced through PSR-7 withBody() may be any StreamInterface.
 */
final class JsonResponse extends Response implements PayloadResponseInterface
{
    /**
     * Constructs a new JsonResponse instance with an optional payload and charset.
     *
     * This constructor SHALL initialize the response body with a JsonStream containing the provided payload.
     * The 'Content-Type' header MUST be set to 'application/json' with the specified charset.
     *
     * @param mixed $payload The JSON-serializable payload to send in the response body. Defaults to an empty array.
     * @param string $charset The character encoding to use in the 'Content-Type' header. Defaults to 'utf-8'.
     * @param array $headers
     */
    public function __construct(mixed $payload = [], string $charset = 'utf-8', array $headers = [])
    {
        $headers['Content-Type'] = ContentType::ApplicationJson->withCharset($charset);

        parent::__construct(
            headers: $headers,
            body: new JsonStream($payload),
        );
    }

    /**
     * Retrieves the payload contained in the response body.
     *
     * Original payload streams return their stored payload. Generic readable,
     * seekable streams are decoded as associative JSON without changing the cursor.
     * Invalid JSON throws JsonException; unreadable, detached or non-seekable
     * generic streams throw RuntimeException rather than being consumed.
     *
     * @return mixed the decoded JSON payload
     *
     * @throws JsonException
     * @throws RuntimeException
     */
    public function getPayload(): mixed
    {
        $body = $this->getBody();
        if ($body instanceof PayloadStreamInterface) {
            return $body->getPayload();
        }

        if (!$body->isReadable() || !$body->isSeekable()) {
            throw new RuntimeException('A generic JSON body must be readable and seekable to retrieve its payload.');
        }

        $position = $body->tell();
        try {
            $body->rewind();
            return json_decode($body->getContents(), true, 512, \JSON_THROW_ON_ERROR);
        } finally {
            $body->seek($position);
        }
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
        $body = $this->getBody();
        return $this->withBody(
            $body instanceof PayloadStreamInterface ? $body->withPayload($payload) : new JsonStream($payload)
        );
    }
}
