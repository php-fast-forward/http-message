Customization
=============

The built-in response classes are convenient, but they are not the only way to use this package.
This page shows the real extension points exposed by the library.

What Is Actually Extensible
---------------------------

- ``PayloadResponseInterface`` for custom payload-aware responses.
- ``PayloadStreamInterface`` for custom payload-aware streams.
- ``JsonStream`` as a reusable building block.
- the enums in :doc:`../api/headers` and :doc:`../api/http-semantics` for consistent application logic.

.. important::

   ``JsonResponse``, ``HtmlResponse``, ``TextResponse``, ``EmptyResponse``, and ``RedirectResponse`` are ``final``.
   Prefer composition or your own implementation instead of inheritance.

Custom Payload Response Example
-------------------------------

If you want a specialized JSON response, implement your own response on top of ``JsonStream``:

.. code-block:: php

   use FastForward\Http\Message\Header\ContentType;
   use FastForward\Http\Message\JsonStream;
   use FastForward\Http\Message\PayloadResponseInterface;
   use Nyholm\Psr7\Response;

   final class ProblemJsonResponse extends Response implements PayloadResponseInterface
   {
       public function __construct(mixed $payload, int $status = 400)
       {
           parent::__construct(
               status: $status,
               headers: [
                   'Content-Type' => ContentType::ApplicationJson->withCharset('utf-8'),
               ],
               body: new JsonStream($payload),
           );
       }

       public function getPayload(): mixed
       {
           return $this->getBody()->getPayload();
       }

       public function withPayload(mixed $payload): self
       {
           return $this->withBody($this->getBody()->withPayload($payload));
       }
   }

Composition Before Reinvention
------------------------------

Before creating a new class, ask whether composition is enough:

.. code-block:: php

   use FastForward\Http\Message\JsonResponse;
   use FastForward\Http\Message\StatusCode;

   $response = (new JsonResponse([
       'type' => 'https://example.com/problems/validation',
       'title' => 'Validation failed',
   ]))
       ->withStatus(StatusCode::UnprocessableEntity->value);

For many applications, this is simpler than creating a new class.

Design Guidance
---------------

- Reach for a custom class only when the same response pattern appears often.
- Keep custom responses PSR-7 compatible so they remain easy to integrate.
- Reuse the package enums instead of duplicating header strings and status codes.
