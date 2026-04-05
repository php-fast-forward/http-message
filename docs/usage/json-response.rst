JSON Response
=============

``JsonResponse`` is the most feature-rich built-in response class in this package.
It is the right default for APIs because it combines three responsibilities:

- it behaves like a normal PSR-7 response;
- it sets the JSON ``Content-Type`` header for you;
- it keeps the original payload accessible through ``getPayload()``.

Constructor Behavior
--------------------

.. code-block:: php

   use FastForward\Http\Message\JsonResponse;

   $response = new JsonResponse(
       payload: ['status' => 'ok'],
       charset: 'utf-8',
       headers: ['X-App' => 'demo'],
   );

By default, ``JsonResponse``:

- uses HTTP 200;
- sets ``Content-Type`` to ``application/json; charset=utf-8`` unless you choose another charset;
- stores a :doc:`json-stream` instance as the body.

Reading the Payload Back
------------------------

.. code-block:: php

   $response = new JsonResponse(['user' => 'Ada']);

   $payload = $response->getPayload();
   echo $payload['user']; // Ada

This is useful when a response object travels through more than one layer of your application.

Changing the Payload Immutably
------------------------------

.. code-block:: php

   $original = new JsonResponse(['step' => 1]);
   $updated  = $original->withPayload(['step' => 2]);

   var_dump($original->getPayload()['step']); // 1
   var_dump($updated->getPayload()['step']);  // 2

The original instance is not modified.

Using Custom Status Codes
-------------------------

.. code-block:: php

   use FastForward\Http\Message\StatusCode;

   $response = (new JsonResponse(['saved' => true]))
       ->withStatus(StatusCode::Created->value);

This keeps the convenience of ``JsonResponse`` while still letting you control HTTP semantics.

When Not to Use It
------------------

Prefer another response type when:

- you want to send HTML to a browser: use :doc:`html-response`;
- you want to send plain text: use :doc:`text-response`;
- you want no body at all: use :doc:`empty-response`;
- you need only a reusable stream: use :doc:`json-stream`.

Common Gotcha
-------------

``JsonResponse`` relies on JSON encoding. If the payload contains a resource or invalid UTF-8 data,
encoding will fail. See :doc:`../advanced/troubleshooting` for recovery strategies.
