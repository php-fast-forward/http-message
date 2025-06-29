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
        StatusCode $status = StatusCode::OK,
    ) {
        parent::__construct(
            status: $status->value,
            headers: ['Content-Type' => 'application/json; charset=utf-8'],
            body: $this->createBody($payload),
            reason: $status->reasonPhrase(),
        );
    }

    private function createBody(mixed $payload): JsonStreamInterface
    {
        return new JsonStream($payload);
    }

    public function getPayload(): mixed
    {
        return $this->getBody()->getPayload();
    }

    public function withPayload(mixed $payload): self
    {
        return $this->withBody($this->createBody($payload));
    }
}
