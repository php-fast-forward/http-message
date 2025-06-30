<?php

namespace FastForward\Http\Message;

/**
 * Enum RequestMethod
 *
 * Represents the set of valid HTTP request methods as defined by the IETF RFC 7231 and related specifications.
 * This enum SHALL be used to strictly define supported request methods within HTTP client and server implementations.
 * Each case corresponds to a standardized HTTP method, expressed as an uppercase string literal.
 *
 * Implementations utilizing this enum MUST validate incoming or outgoing request methods against this set to ensure
 * protocol compliance and interoperability.
 *
 * @package FastForward\Http\Message
 */
enum RequestMethod: string
{
    /** The HEAD method requests the headers for a given resource without the response body. */
    case Head = 'HEAD';

    /** The GET method requests a representation of the specified resource. It MUST NOT have side-effects. */
    case Get = 'GET';

    /** The POST method submits data to be processed, often causing a change in state or side-effects. */
    case Post = 'POST';

    /** The PUT method replaces the target resource with the request payload. */
    case Put = 'PUT';

    /** The PATCH method applies partial modifications to the target resource. */
    case Patch = 'PATCH';

    /** The DELETE method removes the specified resource. */
    case Delete = 'DELETE';

    /** The PURGE method requests that a cached resource be removed, often used with proxy servers. */
    case Purge = 'PURGE';

    /** The OPTIONS method describes the communication options for the target resource. */
    case Options = 'OPTIONS';

    /** The TRACE method performs a message loop-back test along the path to the target resource. */
    case Trace = 'TRACE';

    /** The CONNECT method establishes a tunnel to the target resource, often used with HTTPS proxies. */
    case Connect = 'CONNECT';

    /**
     * Returns true if the method is considered safe (does not modify server state).
     *
     * @return bool
     */
    public function isSafe(): bool
    {
        return in_array($this, [self::Get, self::Head, self::Options, self::Trace], true);
    }

    /**
     * Returns true if the method is idempotent (multiple identical requests have the same effect as a single one).
     *
     * @return bool
     */
    public function isIdempotent(): bool
    {
        return in_array($this, [self::Get, self::Head, self::Put, self::Delete, self::Options, self::Trace], true);
    }

    /**
     * Returns true if the method is considered cacheable by default.
     *
     * @return bool
     */
    public function isCacheable(): bool
    {
        return in_array($this, [self::Get, self::Head], true);
    }
}
