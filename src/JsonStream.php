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
    /**
     * @var resource The underlying stream resource containing the JSON-encoded data.
     *               This resource MUST be a writable PHP stream.
     */
    private $stream;

    /**
     * @var mixed The original payload data prior to JSON encoding.
     *            This MAY be any JSON-encodable PHP type, excluding resources.
     */
    private mixed $payload;

    /**
     * Constructs a new JsonStream instance with the provided payload.
     *
     * The payload SHALL be JSON-encoded and written to an in-memory PHP stream. The original payload is retained
     * in decoded form for later retrieval via {@see getPayload()}.
     *
     * @param mixed $payload The data to encode as JSON. MUST be JSON-encodable. Resources are explicitly prohibited.
     * @param int $encodingOptions Optional JSON encoding flags as defined by {@see json_encode()}. Defaults to 0.
     */
    public function __construct(mixed $payload, private int $encodingOptions = 0)
    {
        $this->setPayload($payload);

        parent::__construct($this->stream);
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

    /**
     * Sets the payload and updates the underlying stream with its JSON-encoded form.
     *
     * This method SHALL encode the payload as JSON, write it to a temporary stream, and rewind the stream pointer.
     * It also retains the original decoded payload for later access.
     *
     * @param mixed $data The data to encode as JSON. MUST be JSON-encodable.
     * @return self The current instance for fluent chaining.
     *
     * @throws \InvalidArgumentException If the data contains a resource.
     * @throws \JsonException If JSON encoding fails.
     */
    private function setPayload(mixed $data): self
    {
        $contents = $this->jsonEncode($data, $this->encodingOptions);

        $this->payload = $data;
        $this->stream = fopen('php://temp', 'wb+');

        $this->write($contents);
        $this->rewind();

        return $this;
    }

    /**
     * Retrieves the decoded payload previously provided to the stream.
     *
     * @return mixed The original decoded payload, which MAY be of any JSON-encodable PHP type.
     */
    public function getPayload(): mixed
    {
        return $this->payload;
    }

    /**
     * Returns a new instance with the updated payload encoded as JSON.
     *
     * This method SHALL NOT modify the current instance. It MUST return a cloned instance with the new payload
     * written to its stream.
     *
     * @param mixed $data The new data to encode as JSON. MUST be JSON-encodable.
     * @return self A new instance with the updated JSON payload.
     *
     * @throws \InvalidArgumentException If the data contains a resource.
     * @throws \JsonException If JSON encoding fails.
     */
    public function withPayload(mixed $data): self
    {
        return (clone $this)->setPayload($data);
    }
}
