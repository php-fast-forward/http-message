Header Utilities
================

The header helpers in this package cover a narrow but practical set of repetitive HTTP tasks:
content negotiation, content type inspection, encoding support, transfer coding detection,
and authorization parsing.

.. list-table:: Public Header-Related Types
   :header-rows: 1

   * - Name
     - Kind
     - Primary Use
   * - ``Accept``
     - Enum
     - Choose the best response format from an ``Accept`` header.
   * - ``ContentType``
     - Enum
     - Parse, inspect, and build ``Content-Type`` values.
   * - ``ContentEncoding``
     - Enum
     - Evaluate whether a compression format is acceptable.
   * - ``TransferEncoding``
     - Enum
     - Detect chunked transfer coding.
   * - ``Authorization``
     - Enum with helper methods
     - Parse supported ``Authorization`` schemes into credential objects.
   * - ``AuthorizationCredential``
     - Interface
     - Marker interface implemented by all parsed authorization credentials.

Accept
------

``Accept::getBestMatch()`` compares a raw ``Accept`` header against the list of response types your application supports.
It understands:

- q-values such as ``application/json;q=0.9``;
- wildcard ranges such as ``*/*`` and ``text/*``;
- preference ordering between more and less specific types.

This makes it a good fit for endpoints that can return either HTML or JSON.

ContentType
-----------

``ContentType`` is useful when you want to avoid manual string parsing:

- ``fromHeaderString()`` extracts the enum case from a header value such as ``application/json; charset=utf-8``;
- ``getCharset()`` returns the declared charset when present;
- ``withCharset()`` builds a correctly formatted header string;
- ``isJson()``, ``isXml()``, ``isText()``, and ``isMultipart()`` make semantic checks explicit.

ContentEncoding
---------------

``ContentEncoding::isSupported()`` answers a common server-side question:
"may I send this compressed representation to this client?"

The helper handles:

- explicit support such as ``gzip, br``;
- explicit rejection with ``q=0``;
- wildcard support;
- the legacy ``x-gzip`` alias.

TransferEncoding
----------------

``TransferEncoding::isChunked()`` performs a tolerant, case-insensitive check for ``chunked`` inside a raw
``Transfer-Encoding`` header. It is useful when you need to inspect inbound or outbound message framing behavior.

Authorization
-------------

``Authorization`` provides three entry points:

- ``parse()`` for a raw authorization header string;
- ``fromHeaderCollection()`` for a header array;
- ``fromRequest()`` for a PSR-7 request.

All helpers return either an ``AuthorizationCredential`` implementation or ``null`` when the header is missing,
unsupported, or malformed.

Supported Credential Types
--------------------------

.. list-table:: Parsed Authorization Credentials
   :header-rows: 1

   * - Scheme
     - Returned Class
     - Main Properties
   * - ``ApiKey``
     - ``ApiKeyCredential``
     - ``key``
   * - ``Basic``
     - ``BasicCredential``
     - ``username``, ``password``
   * - ``Bearer``
     - ``BearerCredential``
     - ``token``
   * - ``Digest``
     - ``DigestCredential``
     - ``username``, ``realm``, ``nonce``, ``uri``, ``response``, and other digest fields
   * - ``AWS4-HMAC-SHA256``
     - ``AwsCredential``
     - ``algorithm``, ``credentialScope``, ``signedHeaders``, ``signature``

Beginner Notes
--------------

- These helpers parse and normalize data. They do not authenticate users by themselves.
- Treat every returned credential object as sensitive data.
- ``Authorization`` expects one of the supported schemes defined by the package.

For runnable examples, see :doc:`../usage/header-utilities`.
