HTTP Semantics
==============

Beyond response shortcuts and header helpers, this package exposes two enums that help keep HTTP decisions readable:

- ``RequestMethod`` for request method behavior;
- ``StatusCode`` for status code values and categories.

RequestMethod
-------------

``RequestMethod`` models common HTTP methods and adds three semantic helpers:

- ``isSafe()``
- ``isIdempotent()``
- ``isCacheable()``

These helpers are useful when your application logic depends on HTTP semantics rather than raw strings.

.. code-block:: php

   use FastForward\Http\Message\RequestMethod;

   if (RequestMethod::Delete->isIdempotent()) {
       // Safe to reason about repeated DELETE attempts in an idempotent way.
   }

.. list-table:: Selected Method Semantics
   :header-rows: 1

   * - Method
     - Safe
     - Idempotent
     - Cacheable
   * - ``GET``
     - Yes
     - Yes
     - Yes
   * - ``HEAD``
     - Yes
     - Yes
     - Yes
   * - ``POST``
     - No
     - No
     - No
   * - ``PUT``
     - No
     - Yes
     - No
   * - ``DELETE``
     - No
     - Yes
     - No

StatusCode
----------

``StatusCode`` is a typed enum for HTTP status codes. It provides:

- the integer code through ``value`` or ``getCode()``;
- a readable reason phrase through ``getReasonPhrase()``;
- category helpers such as ``isSuccess()`` and ``isError()``;
- category names through ``getCategory()``.

.. code-block:: php

   use FastForward\Http\Message\StatusCode;

   $status = StatusCode::Created;

   echo $status->getCode();         // 201
   echo $status->getReasonPhrase(); // Created
   echo $status->getCategory();     // Success

   if ($status->isSuccess()) {
       // Continue with the happy path.
   }

When to Use These Enums
-----------------------

Use them when you want:

- application logic that reads more clearly than raw strings or integers;
- fewer "magic values" in controllers, middleware, or responders;
- a central place for HTTP method and status code meaning.

See also :doc:`../usage/use-cases`.
