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
    /** @var string The HEAD method requests the headers for a given resource without the response body. */
    case HEAD = 'HEAD';

    /** @var string The GET method requests a representation of the specified resource. It MUST NOT have side-effects. */
    case GET = 'GET';

    /** @var string The POST method submits data to be processed, often causing a change in state or side-effects. */
    case POST = 'POST';

    /** @var string The PUT method replaces the target resource with the request payload. */
    case PUT = 'PUT';

    /** @var string The PATCH method applies partial modifications to the target resource. */
    case PATCH = 'PATCH';

    /** @var string The DELETE method removes the specified resource. */
    case DELETE = 'DELETE';

    /** @var string The PURGE method requests that a cached resource be removed, often used with proxy servers. */
    case PURGE = 'PURGE';

    /** @var string The OPTIONS method describes the communication options for the target resource. */
    case OPTIONS = 'OPTIONS';

    /** @var string The TRACE method performs a message loop-back test along the path to the target resource. */
    case TRACE = 'TRACE';

    /** @var string The CONNECT method establishes a tunnel to the target resource, often used with HTTPS proxies. */
    case CONNECT = 'CONNECT';
}
