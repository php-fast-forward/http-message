Integration
===========

Fast Forward HTTP Message is designed to work seamlessly with any PSR-7 compatible framework or library. You can use its response and header classes in any context where PSR-7 messages are accepted.

**Integration Tips:**
--------------------

- Use the provided response classes (``JsonResponse``, ``TextResponse``, ``EmptyResponse``, ``RedirectResponse``) as drop-in replacements for standard PSR-7 responses.
- All classes are immutable and strictly typed, making them safe for use in concurrent or asynchronous environments.
- The header utility classes can be used to parse, validate, and construct HTTP headers in a robust and reusable way.
- For dependency injection, register the classes in your PSR-11 container.

**Extending the Library:**
-------------------------

You may extend or compose the provided classes to fit your application's needs. All interfaces are public and designed for extension.

.. code-block:: php

   // Example of extending a custom response
   class MyCustomResponse extends \FastForward\Http\Message\JsonResponse {
       // Custom logic here
   }

**Advanced Tips:**

- Combine with :doc:`../api/headers` for custom authentication.
- Use ``withPayload()`` for dynamic responses.
- Easily integrate with PSR-15 middlewares.

**See also:** :doc:`../api/responses`, :doc:`../api/payload`, :doc:`../usage/use-cases`
