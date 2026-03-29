Quickstart
==========

Basic Usage Example
-------------------

.. code-block:: php

   use FastForward\Http\Message\JsonResponse;

   $response = new JsonResponse(['success' => true]);

   echo $response->getStatusCode(); // 200
   echo $response->getHeaderLine('Content-Type'); // application/json; charset=utf-8
   echo (string) $response->getBody(); // {"success":true}

Immutability Example
--------------------

.. code-block:: php

   $newResponse = $response->withPayload(['success' => false]);

   echo $response->getPayload()['success']; // true
   echo $newResponse->getPayload()['success']; // false
