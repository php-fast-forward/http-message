Standards and RFCs
==================

The package is easier to use when you know which standards shaped its API.

.. list-table:: Key Standards Referenced by the Package
   :header-rows: 1

   * - Standard
     - Why It Matters Here
   * - `PSR-7 <https://www.php-fig.org/psr/psr-7/>`_
     - Defines the request, response, URI, and stream interfaces used throughout the package.
   * - `PSR-11 <https://www.php-fig.org/psr/psr-11/>`_
     - Relevant when you integrate the package into a container-driven application.
   * - `PSR-15 <https://www.php-fig.org/psr/psr-15/>`_
     - Relevant for middleware and request-handler based applications.
   * - `RFC 9110 <https://datatracker.ietf.org/doc/html/rfc9110>`_
     - General HTTP semantics, status codes, and field behavior.
   * - `RFC 7230 <https://datatracker.ietf.org/doc/html/rfc7230>`_
     - Transfer coding and message syntax details used by ``TransferEncoding``.
   * - `RFC 7235 <https://datatracker.ietf.org/doc/html/rfc7235>`_
     - Authentication framework behind the ``Authorization`` helpers.
   * - `RFC 7616 <https://datatracker.ietf.org/doc/html/rfc7616>`_
     - Digest authentication parsing behavior.
   * - `RFC 7617 <https://datatracker.ietf.org/doc/html/rfc7617>`_
     - Basic authentication semantics.
   * - `RFC 6750 <https://datatracker.ietf.org/doc/html/rfc6750>`_
     - Bearer token usage.
   * - `RFC 7932 <https://datatracker.ietf.org/doc/html/rfc7932>`_
     - Brotli compression.
   * - `RFC 8878 <https://datatracker.ietf.org/doc/html/rfc8878>`_
     - Zstandard compression.
   * - `RFC 2119 <https://datatracker.ietf.org/doc/html/rfc2119>`_
     - Explains the requirement language used throughout the codebase documentation.
