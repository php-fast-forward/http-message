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

use Psr\Http\Message\StreamInterface;

/**
 * Interface PayloadStreamInterface.
 *
 * Extends the PSR-7 StreamInterface to provide additional functionality for payload handling.
 * Implementations of this interface MUST support both standard stream operations and structured payload manipulation.
 *
 * @package FastForward\Http\Message
 */
interface PayloadStreamInterface extends PayloadAwareInterface, PayloadImmutableInterface, StreamInterface {}
