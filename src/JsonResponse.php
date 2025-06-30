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
