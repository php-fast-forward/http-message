Use Cases
=========

This section demonstrates common use cases for the library.

**Creating a JSON Response**
---------------------------

.. code-block:: php

   use FastForward\Http\Message\JsonResponse;
   // Create a JSON response
   $response = new JsonResponse(['data' => 123]);

   // Set a custom status code
   $response = $response->withStatus(201);

   // Add custom headers
   $response = $response->withHeader('X-Custom', 'value');

**Working with Payloads**
------------------------

.. code-block:: php

   // Get the payload
   $payload = $response->getPayload();
   // Immutability: new object with new payload
   $newResponse = $response->withPayload(['foo' => 'bar']);

**Empty Responses**
------------------

.. code-block:: php

   use FastForward\Http\Message\EmptyResponse;
   // Create an HTTP 204 response
   $empty = new EmptyResponse();

**Redirect Responses**
---------------------

.. code-block:: php

   use FastForward\Http\Message\RedirectResponse;
   // Permanent redirect (301)
   $redirect = new RedirectResponse('https://example.com', true);

**Practical Tips:**

- Always use the ``with*`` methods to maintain immutability.
- Combine with :doc:`../api/headers` for advanced header manipulation.
- For framework integration, use the responses directly or register them in your container.

**See also:** :doc:`../api/responses`, :doc:`../api/payload`, :doc:`../api/headers`
