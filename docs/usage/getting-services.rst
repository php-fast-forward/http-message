Getting Services
================

This package does not register itself in a container. In most applications, that is fine:
the built-in classes are small value objects and are usually instantiated directly where
you need them.

Direct Instantiation
--------------------

.. code-block:: php

   use FastForward\Http\Message\JsonResponse;

   $response = new JsonResponse([
       'message' => 'Hello',
   ]);

This is the recommended starting point for most users.

Using a Small Application Factory
---------------------------------

If you want one place to build common responses, create a small factory in your application:

.. code-block:: php

   use FastForward\Http\Message\EmptyResponse;
   use FastForward\Http\Message\JsonResponse;
   use FastForward\Http\Message\RedirectResponse;

   final class AppResponseFactory
   {
       public function json(mixed $payload): JsonResponse
       {
           return new JsonResponse($payload);
       }

       public function noContent(): EmptyResponse
       {
           return new EmptyResponse();
       }

       public function redirect(string $location): RedirectResponse
       {
           return new RedirectResponse($location);
       }
   }

This pattern works well with any container, even when the container syntax differs
between frameworks.

When You Need Container Integration
-----------------------------------

If your project already standardizes around PSR-11 or PSR-17 factories, use
`fast-forward/http-factory <https://github.com/php-fast-forward/http-factory>`_.
That companion package is the right place for service-provider and factory concerns.

Beginner Guidance
-----------------

- Start with direct ``new`` expressions. They are simple and explicit.
- Introduce an application factory only when you repeat the same response wiring in multiple places.
- Reach for a separate factory package only when your project already depends on a container-heavy setup.
