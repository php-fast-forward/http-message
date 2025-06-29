<?php

namespace FastForward\Http\Message;

/**
 * Enum StatusCode
 *
 * Defines HTTP status codes in accordance with the IETF RFC 9110 and related specifications.
 * This enum provides a structured representation of common HTTP response codes, grouped by their respective categories:
 *
 * - Informational (1xx)
 * - Successful (2xx)
 * - Redirection (3xx)
 * - Client Errors (4xx)
 * - Server Errors (5xx)
 *
 * All status codes MUST adhere to the official HTTP specification and SHALL be used consistently within HTTP responses.
 *
 * @package FastForward\Http\Message
 */
enum StatusCode: int
{
    // Informational 1xx

    /** @var int The server has received the request headers and the client SHOULD proceed to send the request body. */
    case CONTINUE = 100;

    /** @var int The requester has asked the server to switch protocols. */
    case SWITCHING_PROTOCOLS = 101;

    /** @var int The server has received and is processing the request, but no response is available yet. */
    case PROCESSING = 102;

    /** @var int Used to return some response headers before the final HTTP message. */
    case EARLY_HINTS = 103;

    // Successful 2xx

    /** @var int The request has succeeded. */
    case OK = 200;

    /** @var int The request has been fulfilled and has resulted in the creation of a new resource. */
    case CREATED = 201;

    /** @var int The request has been accepted for processing, but the processing has not been completed. */
    case ACCEPTED = 202;

    /** @var int The request was successful, but the enclosed payload has been modified from that of the origin server. */
    case NON_AUTHORITATIVE_INFORMATION = 203;

    /** @var int The server successfully processed the request and is not returning any content. */
    case NO_CONTENT = 204;

    /** @var int The server successfully processed the request, but is not returning any content, and requests that the requester reset the document view. */
    case RESET_CONTENT = 205;

    /** @var int The server is delivering only part of the resource due to a range header sent by the client. */
    case PARTIAL_CONTENT = 206;

    /** @var int The message body that follows is an XML message and can contain a number of separate response codes. */
    case MULTI_STATUS = 207;

    /** @var int The members of a DAV binding have already been enumerated in a previous reply to this request, and are not being included again. */
    case ALREADY_REPORTED = 208;

    /** @var int The server has fulfilled a request for the resource, and the response is a representation of the result of one or more instance-manipulations. */
    case IM_USED = 226;

    // Redirection 3xx

    /** @var int Indicates multiple options for the resource from which the client may choose. */
    case MULTIPLE_CHOICES = 300;

    /** @var int This and all future requests SHOULD be directed to the given URI. */
    case MOVED_PERMANENTLY = 301;

    /** @var int The resource resides temporarily under a different URI. */
    case FOUND = 302;

    /** @var int The response to the request can be found under another URI using a GET method. */
    case SEE_OTHER = 303;

    /** @var int Indicates the resource has not been modified since the version specified by the request headers. */
    case NOT_MODIFIED = 304;

    /** @var int The requested resource is only available through a proxy, whose address is provided in the response. */
    case USE_PROXY = 305;

    /** @var int Reserved for future use. */
    case RESERVED = 306;

    /** @var int Instructs the client to repeat the request with a different URI. */
    case TEMPORARY_REDIRECT = 307;

    /** @var int The request and all future requests SHOULD be repeated using another URI. */
    case PERMANENT_REDIRECT = 308;

    // Client Errors 4xx

    /** @var int The server cannot process the request due to a client error. */
    case BAD_REQUEST = 400;

    /** @var int Authentication is required and has failed or has not yet been provided. */
    case UNAUTHORIZED = 401;

    /** @var int Payment is required to access the requested resource. */
    case PAYMENT_REQUIRED = 402;

    /** @var int The client does not have permission to access the resource. */
    case FORBIDDEN = 403;

    /** @var int The requested resource could not be found. */
    case NOT_FOUND = 404;

    /** @var int A request method is not supported for the requested resource. */
    case METHOD_NOT_ALLOWED = 405;

    /** @var int The requested resource is capable of generating only content not acceptable according to the Accept headers. */
    case NOT_ACCEPTABLE = 406;

    /** @var int Proxy authentication is required to access the resource. */
    case PROXY_AUTHENTICATION_REQUIRED = 407;

    /** @var int The client did not produce a request within the time that the server was prepared to wait. */
    case REQUEST_TIMEOUT = 408;

    /** @var int The request could not be completed due to a conflict with the current state of the resource. */
    case CONFLICT = 409;

    /** @var int The resource requested is no longer available and will not be available again. */
    case GONE = 410;

    /** @var int The request did not specify the length of its content, which is required by the requested resource. */
    case LENGTH_REQUIRED = 411;

    /** @var int The server does not meet one of the preconditions specified by the requester. */
    case PRECONDITION_FAILED = 412;

    /** @var int The request is larger than the server is willing or able to process. */
    case PAYLOAD_TOO_LARGE = 413;

    /** @var int The URI provided was too long for the server to process. */
    case URI_TOO_LONG = 414;

    /** @var int The server does not support the media format of the requested data. */
    case UNSUPPORTED_MEDIA_TYPE = 415;

    /** @var int The client has asked for a portion of the file, but the server cannot supply that portion. */
    case RANGE_NOT_SATISFIABLE = 416;

    /** @var int The server cannot meet the expectations specified in the Expect request header. */
    case EXPECTATION_FAILED = 417;

    /** @var int This code is returned by HTCPCP-compliant teapots. */
    case IM_A_TEAPOT = 418;

    /** @var int The request was directed at a server that is not able to produce a response. */
    case MISDIRECTED_REQUEST = 421;

    /** @var int The request was well-formed but was unable to be followed due to semantic errors. */
    case UNPROCESSABLE_ENTITY = 422;

    /** @var int The resource that is being accessed is locked. */
    case LOCKED = 423;

    /** @var int The request failed due to failure of a previous request. */
    case FAILED_DEPENDENCY = 424;

    /** @var int Indicates the server is unwilling to risk processing a request that might be replayed. */
    case TOO_EARLY = 425;

    /** @var int The client should switch to a different protocol such as TLS/1.0. */
    case UPGRADE_REQUIRED = 426;

    /** @var int The origin server requires the request to be conditional. */
    case PRECONDITION_REQUIRED = 428;

    /** @var int The user has sent too many requests in a given amount of time. */
    case TOO_MANY_REQUESTS = 429;

    /** @var int The server is unwilling to process the request because its header fields are too large. */
    case REQUEST_HEADER_FIELDS_TOO_LARGE = 431;

    /** @var int The requested resource is unavailable for legal reasons. */
    case UNAVAILABLE_FOR_LEGAL_REASONS = 451;

    // Server Errors 5xx

    /** @var int The server encountered an unexpected condition that prevented it from fulfilling the request. */
    case INTERNAL_SERVER_ERROR = 500;

    /** @var int The server does not support the functionality required to fulfill the request. */
    case NOT_IMPLEMENTED = 501;

    /** @var int The server, while acting as a gateway or proxy, received an invalid response from the upstream server. */
    case BAD_GATEWAY = 502;

    /** @var int The server is currently unavailable due to maintenance or overload. */
    case SERVICE_UNAVAILABLE = 503;

    /** @var int The server did not receive a timely response from an upstream server. */
    case GATEWAY_TIMEOUT = 504;

    /** @var int The server does not support the HTTP protocol version used in the request. */
    case VERSION_NOT_SUPPORTED = 505;

    /** @var int Transparent content negotiation for the request results in a circular reference. */
    case VARIANT_ALSO_NEGOTIATES = 506;

    /** @var int The server is unable to store the representation needed to complete the request. */
    case INSUFFICIENT_STORAGE = 507;

    /** @var int The server detected an infinite loop while processing a request. */
    case LOOP_DETECTED = 508;

    /** @var int Further extensions to the request are required for the server to fulfill it. */
    case NOT_EXTENDED = 510;

    /** @var int The client needs to authenticate to gain network access. */
    case NETWORK_AUTHENTICATION_REQUIRED = 511;

    /**
     * Returns a human-readable description of the status code.
     *
     * The description is derived from the enum name, replacing underscores with spaces and capitalizing each word.
     *
     * @return string The reason phrase corresponding to the status code.
     */
    public function reasonPhrase(): string
    {
        $phrase = str_replace('_', ' ', strtolower($this->name));

        return ucwords($phrase);
    }
}
