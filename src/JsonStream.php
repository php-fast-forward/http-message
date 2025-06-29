<?php

namespace FastForward\Http\Message;

use Nyholm\Psr7\Stream;

/**
 * Class JsonStream
 *
 * Extends Nyholm's PSR-7 Stream implementation to provide JSON-specific stream functionality.
 * This class SHALL encapsulate a JSON-encoded payload within a PHP stream, while retaining the original
 * payload in a decoded form for convenient access.
 *
 * Implementations MUST properly handle JSON encoding errors and enforce the prohibition of resource types
 * within JSON payloads.
 *
 * @package FastForward\Http\Message
 */
final class JsonStream extends Stream implements JsonStreamInterface
{
    public const ENCODING_OPTIONS = JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

    /**
     * Constructs a new JsonStream instance with the provided payload.
     *
     * The payload SHALL be JSON-encoded and written to an in-memory PHP stream. The original payload is retained
     * in decoded form for later retrieval via {@see getPayload()}.
     *
     * @param mixed $payload The data to encode as JSON. MUST be JSON-encodable. Resources are explicitly prohibited.
     * @param int $encodingOptions Optional JSON encoding flags as defined by {@see json_encode()}. Defaults to 0.
     */
    public function __construct(
        private mixed $payload,
        private int $encodingOptions = self::ENCODING_OPTIONS
    ) {
        parent::__construct(fopen('php://temp', 'wb+'));

        $this->write($this->jsonEncode($this->payload, $this->encodingOptions));
        $this->rewind();
    }

    /**
     * Encodes the given data as JSON, enforcing proper error handling.
     *
     * If the provided data is a resource, this method SHALL throw an {@see \InvalidArgumentException} as resources
     * cannot be represented in JSON format.
     *
     * @param mixed $data The data to encode as JSON.
     * @param int $encodingOptions JSON encoding options, combined with JSON_THROW_ON_ERROR.
     * @return string The JSON-encoded string representation of the data.
     *
     * @throws \InvalidArgumentException If the data contains a resource.
     * @throws \JsonException If JSON encoding fails.
     */
    private function jsonEncode(mixed $data, int $encodingOptions): string
    {
        if (is_resource($data)) {
            throw new \InvalidArgumentException('Cannot JSON encode resources');
        }

        // Clear json_last_error()
        json_encode(null);

        return json_encode($data, $encodingOptions | JSON_THROW_ON_ERROR);
    }

    public function getPayload(): mixed
    {
        return $this->payload;
    }

    public function withPayload(mixed $payload): self
    {
        return new self($payload);
    }
}
