Use Cases
=========

This page connects multiple classes together into practical flows.
If you are wondering "what should I do in a real endpoint or middleware?",
start here.

Create an API Response
----------------------

``JsonResponse`` is the default choice for JSON APIs:

.. code-block:: php

   use FastForward\Http\Message\JsonResponse;
   use FastForward\Http\Message\StatusCode;

   $response = (new JsonResponse([
       'id' => 42,
       'name' => 'Ada',
   ]))
       ->withStatus(StatusCode::Created->value)
       ->withHeader('Location', '/users/42');

The response body is JSON, the payload stays accessible, and the object remains a normal PSR-7 response.

Serve HTML or Plain Text
------------------------

Use HTML or text responses when JSON would be unnecessary:

.. code-block:: php

   use FastForward\Http\Message\HtmlResponse;
   use FastForward\Http\Message\TextResponse;

   $page = new HtmlResponse('<h1>Welcome back</h1>');
   $text = new TextResponse('Maintenance mode is enabled.');

Return No Body on Success
-------------------------

If the client only needs to know that the operation succeeded, return ``EmptyResponse``:

.. code-block:: php

   use FastForward\Http\Message\EmptyResponse;

   $response = new EmptyResponse([
       'X-Deleted-Id' => '42',
   ]);

This keeps your intent explicit and automatically uses HTTP 204.

Redirect After a Form Submission
--------------------------------

Use ``RedirectResponse`` for 301 and 302, then refine the status if your workflow needs a different redirect code:

.. code-block:: php

   use FastForward\Http\Message\RedirectResponse;
   use FastForward\Http\Message\StatusCode;

   $response = (new RedirectResponse('/orders/42'))
       ->withStatus(StatusCode::SeeOther->value);

This pattern is useful for "POST, then redirect" flows where HTTP 303 is the better semantic match.

Negotiate the Best Response Type
--------------------------------

``Accept`` helps you choose between multiple response formats:

.. code-block:: php

   use FastForward\Http\Message\Header\Accept;
   use FastForward\Http\Message\HtmlResponse;
   use FastForward\Http\Message\JsonResponse;

   $bestMatch = Accept::getBestMatch(
       'text/html;q=0.8, application/json;q=0.9',
       [Accept::ApplicationJson, Accept::TextHtml],
   );

   $response = match ($bestMatch) {
       Accept::TextHtml => new HtmlResponse('<h1>User profile</h1>'),
       default => new JsonResponse(['user' => 'Ada']),
   };

Inspect Authorization in Middleware
-----------------------------------

``Authorization`` converts raw header strings into structured credential objects:

.. code-block:: php

   use FastForward\Http\Message\Header\Authorization;
   use FastForward\Http\Message\Header\Authorization\BearerCredential;

   $credential = Authorization::fromHeaderCollection([
       'Authorization' => 'Bearer my-secret-token',
   ]);

   if ($credential instanceof BearerCredential) {
       $token = $credential->token;
   }

Check Compression Support
-------------------------

Use ``ContentEncoding`` to decide whether a compressed response is acceptable:

.. code-block:: php

   use FastForward\Http\Message\Header\ContentEncoding;

   if (ContentEncoding::isSupported(ContentEncoding::Brotli, 'gzip, br')) {
       // You can safely serve Brotli-compressed content.
   }

Follow-Up Reading
-----------------

- :doc:`json-response`
- :doc:`json-stream`
- :doc:`header-utilities`
- :doc:`../api/http-semantics`
