Integration
===========

Fast Forward HTTP Message is designed to fit into existing PSR-based applications with very little setup.
The package does not try to replace your framework or HTTP stack; it provides small, composable pieces instead.

Using the Responses in Request Handlers
---------------------------------------

Because all built-in responses are standard PSR-7 response objects, they can be returned directly from controllers,
request handlers, or middleware pipelines:

.. code-block:: php

   use FastForward\Http\Message\JsonResponse;
   use FastForward\Http\Message\StatusCode;

   $response = (new JsonResponse(['ok' => true]))
       ->withStatus(StatusCode::Accepted->value);

Integrating in PSR-15 Middleware
--------------------------------

Header helpers are especially useful at middleware boundaries:

.. code-block:: php

   use FastForward\Http\Message\Header\Authorization;
   use FastForward\Http\Message\Header\Authorization\BearerCredential;
   use FastForward\Http\Message\JsonResponse;
   use Psr\Http\Message\ResponseInterface;
   use Psr\Http\Message\ServerRequestInterface;

   function authorize(ServerRequestInterface $request): ResponseInterface
   {
       $credential = Authorization::fromRequest($request);

       if (! $credential instanceof BearerCredential) {
           return new JsonResponse(['error' => 'Missing bearer token']);
       }

       return new JsonResponse(['token' => $credential->token]);
   }

Container and Factory Integration
---------------------------------

This package has no built-in service provider. That is usually fine because the response classes are simple to instantiate.
If your application needs formal PSR-11 or PSR-17 integration, use
`fast-forward/http-factory <https://github.com/php-fast-forward/http-factory>`_.

Interoperability Guidelines
---------------------------

- Treat the built-in classes as drop-in PSR-7 responses.
- Use enum helpers close to HTTP boundaries, where raw headers and methods enter your application.
- Keep transport concerns separate from domain logic by returning the final response only at the edge.

See also :doc:`customization` and :doc:`troubleshooting`.
