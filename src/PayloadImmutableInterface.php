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

/**
 * Interface PayloadAwareInterface.
 *
 * Defines functionality for objects that encapsulate and manage a payload.
 * Implementations of this interface MUST provide immutable methods for accessing and replacing the payload.
 * The payload MAY be of any type supported by the implementation, including arrays, objects, scalars, or null.
 *
 * @internal
 */
interface PayloadImmutableInterface
{
    /**
     * Returns a new instance with the specified payload.
     *
     * This method MUST NOT modify the current instance. It SHALL return a new instance with the updated payload.
     * The payload MAY be of any type supported by the implementation. Implementations MAY throw exceptions if
     * constraints on the payload type are violated.
     *
     * @param mixed $payload the new payload to set in the instance
     *
     * @return self a new instance with the updated payload
     */
    public function withPayload(mixed $payload): self;
}
