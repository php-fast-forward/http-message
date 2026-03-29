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
 */
interface PayloadAwareInterface
{
    /**
     * Retrieves the payload contained within the object.
     *
     * This method MUST return the payload as originally provided or modified.
     * The returned type MAY vary depending on the structure of the payload (e.g., array, object, scalar, or null).
     *
     * @return mixed the payload, which MAY be of any type including array, object, scalar, or null
     */
    public function getPayload(): mixed;
}
