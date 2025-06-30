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

use Psr\Http\Message\ResponseInterface;

/**
 * Interface PayloadResponseInterface.
 *
 * Defines the contract for payload-specific HTTP response implementations.
 * Implementations of this interface MUST provide access to a payload and comply with PSR-7 ResponseInterface.
 *
 * This interface SHALL be used to identify responses intended to transmit payloads with proper headers.
 */
interface PayloadResponseInterface extends PayloadAwareInterface, PayloadImmutableInterface, ResponseInterface {}
