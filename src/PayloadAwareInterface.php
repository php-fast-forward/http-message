<?php

namespace FastForward\Http\Message;

use Psr\Http\Message\StreamInterface;

/**
 * Interface PayloadAwareInterface
 *
 * Provide functionality for JSON payload handling.
 *
 * @package FastForward\Http\Message
 */
interface PayloadAwareInterface
{
    /**
     * Retrieves the decoded JSON payload from the stream.
     *
     * This method MUST return the decoded JSON payload as a native PHP type. The returned type MAY vary depending on
     * the structure of the JSON content (e.g., array, object, int, float, string, bool, or null).
     *
     * @return mixed The decoded JSON payload, which MAY be of any type, including array, object, scalar, or null.
     */
    public function getPayload(): mixed;

    /**
     * Returns a new instance with the provided payload encoded as JSON.
     *
     * This method MUST NOT modify the existing instance; instead, it SHALL return a new instance with the updated
     * JSON payload written to the underlying stream.
     *
     * The provided data MUST be JSON-encodable. If encoding fails, the method MAY throw an exception as defined
     * by the implementation.
     *
     * @param mixed $payload The data to encode as JSON and set as the stream's payload. This MAY be of any type
     *                    supported by json_encode.
     * @return self A new instance with the updated JSON payload.
     */
    public function withPayload(mixed $payload): self;
}
