Empty Response
==============

``EmptyResponse`` exists for the common case where success should not include a body.
It communicates that intent more clearly than returning a generic response with an empty string.

What It Does
------------

- sets the status code to HTTP 204;
- sets the reason phrase to ``No Content``;
- leaves the body empty.

Basic Example
-------------

.. code-block:: php

   use FastForward\Http\Message\EmptyResponse;

   $response = new EmptyResponse();

   echo $response->getStatusCode(); // 204
   echo (string) $response->getBody(); // ''

Adding Headers
--------------

.. code-block:: php

   $response = new EmptyResponse([
       'X-Deleted-Id' => '42',
   ]);

Typical Scenarios
-----------------

``EmptyResponse`` is a good fit for:

- successful DELETE requests;
- update operations where the client does not need a document back;
- webhook acknowledgements;
- endpoints that use headers, not a body, to carry follow-up metadata.

If you need a different success status with no body, you can still call normal PSR-7 methods
such as ``withStatus()`` on the returned response.
