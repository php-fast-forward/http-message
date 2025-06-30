<?php

namespace FastForward\Http\Message;

use Nyholm\Psr7\Response;

/**
 * @method getBody(): JsonStreamInterface
 */
final class JsonResponse extends Response implements JsonResponseInterface
{
    public function __construct(
        mixed $payload = [],
        string $charset = 'utf-8'
    ) {
        parent::__construct(
            headers: ['Content-Type' => 'application/json; charset=' . $charset],
            body: new JsonStream($payload),
        );
    }

    public function getPayload(): mixed
    {
        return $this->getBody()->getPayload();
    }

    public function withPayload(mixed $payload): self
    {
        return $this->withBody($this->getBody()->withPayload($payload));
    }
}
