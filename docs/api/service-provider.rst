Factories and Container Integration
===================================

This package intentionally does not ship with:

- a service provider;
- PSR-17 factories;
- framework-specific bindings.

Why This Is Intentional
-----------------------

Fast Forward HTTP Message is primarily a utility library. Most of its objects are simple enough to instantiate
directly, so forcing a container or framework abstraction would add more ceremony than value.

Recommended Companion Package
-----------------------------

For container integration and PSR-17 factories, use
`fast-forward/http-factory <https://github.com/php-fast-forward/http-factory>`_.
That package is the right place for service-provider concerns in the Fast Forward ecosystem.

Manual Integration
------------------

If you prefer to wire the package manually, register your own application-level factory or service:

.. code-block:: php

   use FastForward\Http\Message\JsonResponse;

   final class ResponseFactory
   {
       public function json(mixed $payload): JsonResponse
       {
           return new JsonResponse($payload);
       }
   }

This keeps your integration explicit and works with any container strategy.
