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

namespace FastForward\Http\Message\Header\Authorization;

/**
 * Interface AuthorizationCredential.
 *
 * Represents the structured credential extracted from an HTTP
 * `Authorization` header. Implementations of this interface MUST model the
 * specific authentication scheme used by the client, such as API Key,
 * Basic, Bearer, Digest, or AWS Signature Version 4.
 *
 * Types implementing this interface SHALL be returned by the
 * {@see FastForward\Http\Message\Header\Authorization::parse()} and related
 * helper methods. They MUST encapsulate all information necessary for
 * downstream authentication logic to validate the user agent.
 *
 * This interface does not enforce any specific methods, but implementors
 * SHOULD expose immutable, well-typed public properties or accessors to
 * represent authentication values in a safe and structured form.
 */
interface AuthorizationCredential {}
