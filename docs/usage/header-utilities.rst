Header Utilities
================

This package includes small, focused helpers for common HTTP header tasks.
They are especially useful when you want typed logic instead of repeating string parsing by hand.

Accept Negotiation
------------------

Use ``Accept::getBestMatch()`` to pick the best response type from the client's ``Accept`` header:

.. code-block:: php

   use FastForward\Http\Message\Header\Accept;

   $bestMatch = Accept::getBestMatch(
       'text/html;q=0.8, application/json;q=0.9',
       [Accept::ApplicationJson, Accept::TextHtml],
   );

The helper understands q-values and wildcard matches such as ``*/*`` and ``text/*``.

Content-Type Parsing
--------------------

Use ``ContentType`` when you need to inspect or build ``Content-Type`` values:

.. code-block:: php

   use FastForward\Http\Message\Header\ContentType;

   $type    = ContentType::fromHeaderString('application/json; charset=utf-8');
   $charset = ContentType::getCharset('application/json; charset=utf-8');

   echo $type?->value; // application/json
   echo $charset;      // utf-8

Content-Encoding Support
------------------------

Use ``ContentEncoding::isSupported()`` to decide whether a compressed representation is acceptable:

.. code-block:: php

   use FastForward\Http\Message\Header\ContentEncoding;

   $supportsBrotli = ContentEncoding::isSupported(
       ContentEncoding::Brotli,
       'gzip, br',
   );

This helper understands explicit rejection with ``q=0``, wildcard support, and the ``x-gzip`` alias.

Transfer-Encoding Detection
---------------------------

Use ``TransferEncoding::isChunked()`` when you need to know whether a message uses chunked transfer coding:

.. code-block:: php

   use FastForward\Http\Message\Header\TransferEncoding;

   $chunked = TransferEncoding::isChunked('gzip, chunked');

Authorization Parsing
---------------------

Use ``Authorization`` to turn raw authorization strings into structured credential objects:

.. code-block:: php

   use FastForward\Http\Message\Header\Authorization;
   use FastForward\Http\Message\Header\Authorization\BasicCredential;

   $credential = Authorization::parse('Basic dXNlcjpwYXNz');

   if ($credential instanceof BasicCredential) {
       echo $credential->username; // user
   }

You can also parse authorization data directly from:

- a header array with ``Authorization::fromHeaderCollection()``;
- a PSR-7 request with ``Authorization::fromRequest()``.

Supported Authorization Schemes
-------------------------------

.. list-table:: Credential Objects Returned by Authorization Helpers
   :header-rows: 1

   * - Scheme
     - Returned Type
     - Notes
   * - ``ApiKey``
     - ``ApiKeyCredential``
     - Treat the whole credential as an opaque secret.
   * - ``Basic``
     - ``BasicCredential``
     - Decodes ``username:password`` from Base64.
   * - ``Bearer``
     - ``BearerCredential``
     - Returns the token without inspecting its contents.
   * - ``Digest``
     - ``DigestCredential``
     - Parses the RFC 7616 key-value pairs.
   * - ``AWS4-HMAC-SHA256``
     - ``AwsCredential``
     - Parses the SigV4 credential scope, signed headers, and signature.

Practical Advice
----------------

- Use these helpers at the edges of your application, such as middleware, request handlers, or responders.
- Prefer enum values over hard-coded strings when you compare known content types or encodings.
- Treat all authorization credentials as sensitive, even when only some fields are marked as sensitive parameters.
