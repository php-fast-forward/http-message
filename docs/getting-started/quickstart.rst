Quickstart
==========

If you only need one example to understand the package, start with ``JsonResponse``.
It is the most common entry point for APIs and also introduces the payload-aware features
provided by this library.

Your First Response
-------------------

.. code-block:: php

   use FastForward\Http\Message\JsonResponse;
   use FastForward\Http\Message\StatusCode;

   $response = (new JsonResponse([
       'success' => true,
       'message' => 'Account created',
   ]))
       ->withStatus(StatusCode::Created->value)
       ->withHeader('X-Request-Id', 'req-123');

   echo $response->getStatusCode();              // 201
   echo $response->getHeaderLine('Content-Type'); // application/json; charset=utf-8
   echo (string) $response->getBody();            // {"success":true,"message":"Account created"}

   $payload = $response->getPayload();
   echo $payload['message'];                      // Account created

Why This Is Useful
------------------

- You return a normal PSR-7 response object.
- The body is JSON-encoded for transport.
- The original payload remains accessible through ``getPayload()``.

Immutability in Practice
------------------------

All PSR-7 mutations return a new instance. The original response stays unchanged:

.. code-block:: php

   $original = new JsonResponse(['success' => true]);
   $updated  = $original->withPayload(['success' => false]);

   var_dump($original->getPayload()['success']); // true
   var_dump($updated->getPayload()['success']);  // false

Choosing Another Built-In Response
----------------------------------

Use the response that matches the intent of your output:

.. code-block:: php

   use FastForward\Http\Message\EmptyResponse;
   use FastForward\Http\Message\HtmlResponse;
   use FastForward\Http\Message\RedirectResponse;
   use FastForward\Http\Message\TextResponse;

   $html     = new HtmlResponse('<h1>Hello</h1>');
   $text     = new TextResponse('Hello from a CLI-style endpoint');
   $empty    = new EmptyResponse();
   $redirect = new RedirectResponse('/login');

Next Steps
----------

- :doc:`../usage/json-response` explains payload-aware JSON responses in more depth.
- :doc:`../usage/header-utilities` shows how to parse and negotiate headers.
- :doc:`../api/http-semantics` documents ``StatusCode`` and ``RequestMethod``.
