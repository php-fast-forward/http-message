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

use Nyholm\Psr7\Stream;

/**
 * Class JsonStream.
 *
 * Provides a JSON-specific stream implementation, extending Nyholm's PSR-7 Stream.
 * This class SHALL encapsulate a JSON-encoded payload within an in-memory PHP stream,
 * while retaining the original decoded payload for convenient retrieval.
 *
 * Implementations of this class MUST properly handle JSON encoding errors and SHALL explicitly
 * prohibit the inclusion of resource types within the JSON payload.
 *
 * @package FastForward\Http\Message
 */
final class JsonStream extends Stream implements JsonStreamInterface
{
    /**
     * JSON encoding flags to be applied by default.
     *
     * The options JSON_THROW_ON_ERROR, JSON_UNESCAPED_SLASHES, and JSON_UNESCAPED_UNICODE
     * SHALL be applied to enforce strict error handling and produce readable JSON output.
     *
     * @var int
     */
    public const ENCODING_OPTIONS = JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

    /**
     * @var mixed The decoded payload provided to the stream. This MUST be JSON-encodable and MUST NOT contain resources.
     */
    private mixed $payload = [];

    /**
     * @var int the JSON encoding options to be applied
     */
    private int $encodingOptions;

    /**
     * Constructs a new JsonStream instance with the provided payload.
     *
     * The payload SHALL be JSON-encoded and written to an in-memory stream. The original payload is retained
     * in its decoded form for later access via getPayload().
     *
     * @param mixed $payload         The data to encode as JSON. MUST be JSON-encodable. Resources are explicitly prohibited.
     * @param int   $encodingOptions Optional JSON encoding flags. If omitted, ENCODING_OPTIONS will be applied.
     */
    public function __construct(mixed $payload = [], int $encodingOptions = self::ENCODING_OPTIONS)
    {
        $this->payload         = $payload;
        $this->encodingOptions = $encodingOptions;

        parent::__construct(fopen('php://temp', 'wb+'));

        $this->write($this->jsonEncode($this->payload, $this->encodingOptions));
        $this->rewind();
    }

    /**
     * Retrieves the decoded payload associated with the stream.
     *
     * This method SHALL return the original JSON-encodable payload provided during construction or via withPayload().
     *
     * @return mixed the decoded payload
     */
    public function getPayload(): mixed
    {
        return $this->payload;
    }

    /**
     * Returns a new instance of the stream with the specified payload.
     *
     * This method MUST return a new JsonStream instance with the body replaced by a stream
     * containing the JSON-encoded form of the new payload. The current instance SHALL remain unchanged.
     *
     * @param mixed $payload the new JSON-encodable payload
     *
     * @return self a new JsonStream instance containing the updated payload
     */
    public function withPayload(mixed $payload): self
    {
        return new self($payload, $this->encodingOptions);
    }

    /**
     * Encodes the given data as JSON, enforcing proper error handling.
     *
     * If the provided data is a resource, this method SHALL throw an \InvalidArgumentException,
     * as resource types are not supported by JSON.
     *
     * @param mixed $data            the data to encode as JSON
     * @param int   $encodingOptions JSON encoding options to apply. JSON_THROW_ON_ERROR will always be enforced.
     *
     * @return string the JSON-encoded string representation of the data
     *
     * @throws \InvalidArgumentException if the data contains a resource
     * @throws \JsonException            if JSON encoding fails
     */
    private function jsonEncode(mixed $data, int $encodingOptions): string
    {
        if (\is_resource($data)) {
            throw new \InvalidArgumentException('Cannot JSON encode resources.');
        }

        // Reset potential previous errors
        json_encode(null);

        return json_encode($data, $encodingOptions | JSON_THROW_ON_ERROR);
    }
}
