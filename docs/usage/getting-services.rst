Getting Services
===============

Fast Forward HTTP Message is a utility library and does not provide a service container. You can instantiate its classes directly as needed. For example:

.. code-block:: php

   use FastForward\Http\Message\JsonResponse;
   $response = new JsonResponse(['foo' => 'bar']);

All classes are strictly typed and designed for direct use in your application code.