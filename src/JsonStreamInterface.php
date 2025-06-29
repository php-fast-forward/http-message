<?php

namespace FastForward\Http\Message;

use Psr\Http\Message\StreamInterface;

/**
 * Interface JsonStreamInterface
 *
 * Extends the PSR-7 StreamInterface to provide additional functionality for JSON payload handling.
 * Implementations of this interface MUST support both standard stream operations and structured JSON payload manipulation.
 *
 * @package FastForward\Http\Message
 */
interface JsonStreamInterface extends StreamInterface, PayloadAwareInterface
{
}
