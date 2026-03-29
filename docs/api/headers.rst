Headers
=======

The library provides utility classes for working with HTTP headers:

.. list-table:: Header Utilities
     :header-rows: 1

     * - Class
         - Description
     * - ``ContentType``
         - Manage the ``Content-Type`` header
     * - ``Accept``
         - Parse and represent the ``Accept`` header
     * - ``ContentEncoding``
         - Handle the ``Content-Encoding`` header
     * - ``TransferEncoding``
         - Handle the ``Transfer-Encoding`` header
     * - ``Authorization``
         - Parse and represent the ``Authorization`` header (API Key, Basic, Bearer, Digest, AWS)

**Authorization Credentials**
----------------------------

The ``Authorization`` header parser returns credential objects implementing ``AuthorizationCredential``:

- ``ApiKeyCredential``
- ``BasicCredential``
- ``BearerCredential``
- ``DigestCredential``
- ``AwsCredential``

Each credential class is immutable and exposes the relevant authentication data in a safe, structured form.

**Example:**

.. code-block:: php

    use FastForward\Http\Message\Header\Authorization;
    $credential = Authorization::fromHeaderCollection($headers);
    if ($credential instanceof AuthorizationCredential) {
        // Handle the credential according to its type
    }

**Advanced Tips:**

- Use the utilities to validate, build, and parse headers robustly.
- Combine with :doc:`responses` for custom responses.
- For advanced authentication, use the credential classes.

**See also:** :doc:`responses`, :doc:`payload`, :doc:`../usage/use-cases`
