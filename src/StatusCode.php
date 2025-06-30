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
    case Continue = 100;

    /** @var int The requester has asked the server to switch protocols. */
    case SwitchingProtocols = 101;

    /** @var int The server has received and is processing the request, but no response is available yet. */
    case Processing = 102;

    /** @var int Used to return some response headers before the final HTTP message. */
    case EarlyHints = 103;

    // Successful 2xx

    /** @var int The request has succeeded. */
    case Ok = 200;

    /** @var int The request has been fulfilled and has resulted in the creation of a new resource. */
    case Created = 201;

    /** @var int The request has been accepted for processing, but the processing has not been completed. */
    case Accepted = 202;

    /** @var int The request was successful, but the enclosed payload has been modified from that of the origin server. */
    case NonAuthoritativeInformation = 203;

    /** @var int The server successfully processed the request and is not returning any content. */
    case NoContent = 204;

    /** @var int The server successfully processed the request, but is not returning any content, and requests that the requester reset the document view. */
    case ResetContent = 205;

    /** @var int The server is delivering only part of the resource due to a range header sent by the client. */
    case PartialContent = 206;

    /** @var int The message body that follows is an XML message and can contain a number of separate response codes. */
    case MultiStatus = 207;

    /** @var int The members of a DAV binding have already been enumerated in a previous reply to this request, and are not being included again. */
    case AlreadyReported = 208;

    /** @var int The server has fulfilled a request for the resource, and the response is a representation of the result of one or more instance-manipulations. */
    case ImUsed = 226;

    // Redirection 3xx

    /** @var int Indicates multiple options for the resource from which the client may choose. */
    case MultipleChoices = 300;

    /** @var int This and all future requests SHOULD be directed to the given URI. */
    case MovedPermanently = 301;

    /** @var int The resource resides temporarily under a different URI. */
    case Found = 302;

    /** @var int The response to the request can be found under another URI using a GET method. */
    case SeeOther = 303;

    /** @var int Indicates the resource has not been modified since the version specified by the request headers. */
    case NotModified = 304;

    /** @var int The requested resource is only available through a proxy, whose address is provided in the response. */
    case UseProxy = 305;

    /** @var int Reserved for future use. */
    case Reserved = 306;

    /** @var int Instructs the client to repeat the request with a different URI. */
    case TemporaryRedirect = 307;

    /** @var int The request and all future requests SHOULD be repeated using another URI. */
    case PermanentRedirect = 308;

    // Client Errors 4xx

    /** @var int The server cannot process the request due to a client error. */
    case BadRequest = 400;

    /** @var int Authentication is required and has failed or has not yet been provided. */
    case Unauthorized = 401;

    /** @var int Payment is required to access the requested resource. */
    case PaymentRequired = 402;

    /** @var int The client does not have permission to access the resource. */
    case Forbidden = 403;

    /** @var int The requested resource could not be found. */
    case NotFound = 404;

    /** @var int A request method is not supported for the requested resource. */
    case MethodNotAllowed = 405;

    /** @var int The requested resource is capable of generating only content not acceptable according to the Accept headers. */
    case NotAcceptable = 406;

    /** @var int Proxy authentication is required to access the resource. */
    case ProxyAuthenticationRequired = 407;

    /** @var int The client did not produce a request within the time that the server was prepared to wait. */
    case RequestTimeout = 408;

    /** @var int The request could not be completed due to a conflict with the current state of the resource. */
    case Conflict = 409;

    /** @var int The resource requested is no longer available and will not be available again. */
    case Gone = 410;

    /** @var int The request did not specify the length of its content, which is required by the requested resource. */
    case LengthRequired = 411;

    /** @var int The server does not meet one of the preconditions specified by the requester. */
    case PreconditionFailed = 412;

    /** @var int The request is larger than the server is willing or able to process. */
    case PayloadTooLarge = 413;

    /** @var int The URI provided was too long for the server to process. */
    case UriTooLong = 414;

    /** @var int The server does not support the media format of the requested data. */
    case UnsupportedMediaType = 415;

    /** @var int The client has asked for a portion of the file, but the server cannot supply that portion. */
    case RangeNotSatisfiable = 416;

    /** @var int The server cannot meet the expectations specified in the Expect request header. */
    case ExpectationFailed = 417;

    /** @var int This code is returned by HTCPCP-compliant teapots. */
    case ImATeapot = 418;

    /** @var int The request was directed at a server that is not able to produce a response. */
    case MisdirectedRequest = 421;

    /** @var int The request was well-formed but was unable to be followed due to semantic errors. */
    case UnprocessableEntity = 422;

    /** @var int The resource that is being accessed is locked. */
    case Locked = 423;

    /** @var int The request failed due to failure of a previous request. */
    case FailedDependency = 424;

    /** @var int Indicates the server is unwilling to risk processing a request that might be replayed. */
    case TooEarly = 425;

    /** @var int The client should switch to a different protocol such as TLS/1.0. */
    case UpgradeRequired = 426;

    /** @var int The origin server requires the request to be conditional. */
    case PreconditionRequired = 428;

    /** @var int The user has sent too many requests in a given amount of time. */
    case TooManyRequests = 429;

    /** @var int The server is unwilling to process the request because its header fields are too large. */
    case RequestHeaderFieldsTooLarge = 431;

    /** @var int The requested resource is unavailable for legal reasons. */
    case UnavailableForLegalReasons = 451;

    // Server Errors 5xx

    /** @var int The server encountered an unexpected condition that prevented it from fulfilling the request. */
    case InternalServerError = 500;

    /** @var int The server does not support the functionality required to fulfill the request. */
    case NotImplemented = 501;

    /** @var int The server, while acting as a gateway or proxy, received an invalid response from the upstream server. */
    case BadGateway = 502;

    /** @var int The server is currently unavailable due to maintenance or overload. */
    case ServiceUnavailable = 503;

    /** @var int The server did not receive a timely response from an upstream server. */
    case GatewayTimeout = 504;

    /** @var int The server does not support the HTTP protocol version used in the request. */
    case VersionNotSupported = 505;

    /** @var int Transparent content negotiation for the request results in a circular reference. */
    case VariantAlsoNegotiates = 506;

    /** @var int The server is unable to store the representation needed to complete the request. */
    case InsufficientStorage = 507;

    /** @var int The server detected an infinite loop while processing a request. */
    case LoopDetected = 508;

    /** @var int Further extensions to the request are required for the server to fulfill it. */
    case NotExtended = 510;

    /** @var int The client needs to authenticate to gain network access. */
    case NetworkAuthenticationRequired = 511;

    /**
     * Returns the numeric HTTP status code.
     *
     * @return int The numeric status code as defined by the HTTP specification.
     */
    public function getCode(): int
    {
        return $this->value;
    }

    /**
     * Returns a human-readable description of the status code.
     *
     * The description is derived from the enum name, replacing underscores with spaces and capitalizing each word.
     *
     * @return string The reason phrase corresponding to the status code.
     */
    public function getReasonPhrase(): string
    {
        return preg_replace('/(?<!^)[A-Z]/', ' $0', $this->name);
    }

    /**
     * Returns the category of the status code.
     *
     * Categories are based on the first digit of the status code:
     * - 1: Informational
     * - 2: Success
     * - 3: Redirection
     * - 4: Client Error
     * - 5: Server Error
     *
     * @return string
     */
    public function getCategory(): string
    {
        return match (intdiv($this->value, 100)) {
            1 => 'Informational',
            2 => 'Success',
            3 => 'Redirection',
            4 => 'Client Error',
            5 => 'Server Error',
        };
    }

    /**
     * Returns true if the status code is informational (1xx).
     *
     * @return bool
     */
    public function isInformational(): bool
    {
        return intdiv($this->value, 100) === 1;
    }

    /**
     * Returns true if the status code indicates success (2xx).
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return intdiv($this->value, 100) === 2;
    }

    /**
     * Returns true if the status code indicates redirection (3xx).
     *
     * @return bool
     */
    public function isRedirection(): bool
    {
        return intdiv($this->value, 100) === 3;
    }

    /**
     * Returns true if the status code indicates a client error (4xx).
     *
     * @return bool
     */
    public function isClientError(): bool
    {
        return intdiv($this->value, 100) === 4;
    }

    /**
     * Returns true if the status code indicates a server error (5xx).
     *
     * @return bool
     */
    public function isServerError(): bool
    {
        return intdiv($this->value, 100) === 5;
    }

    /**
     * Returns true if the status code indicates any type of error (client or server).
     *
     * @return bool
     */
    public function isError(): bool
    {
        return $this->isClientError() || $this->isServerError();
    }
}
