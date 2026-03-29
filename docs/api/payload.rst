Payload Interfaces and Streams
=============================

The library introduces several interfaces and classes for working with payloads:

.. list-table:: Payload Interfaces & Classes
   :header-rows: 1

   * - Name
     - Description
   * - ``PayloadAwareInterface``
     - For objects that encapsulate and manage a payload. Provides ``getPayload()``.
   * - ``PayloadImmutableInterface``
     - For objects that allow immutable replacement of the payload. Provides ``withPayload($payload)``.
   * - ``PayloadResponseInterface``
     - Combines payload access and immutability for PSR-7 responses.
   * - ``JsonStream``
     - Stream implementation that encodes a payload as JSON and retains the original decoded value.

All payload-related classes are strictly typed and immutable.

**Examples:**

.. code-block:: php

   // Working with payload in a JSON response
   use FastForward\Http\Message\JsonResponse;
   $response = new JsonResponse(['foo' => 'bar']);
   $payload = $response->getPayload(); // ['foo' => 'bar']
   $new = $response->withPayload(['baz' => 123]);

   // Using JsonStream to handle JSON as a stream
   use FastForward\Http\Message\JsonStream;
   $stream = new JsonStream(['foo' => 'bar']);
   $json = (string) $stream; // '{"foo":"bar"}'
   $payload = $stream->getPayload();

**Advanced Tips:**

- Payloads can be any type serializable to JSON.
- Use ``withPayload()`` to ensure immutability and avoid side effects.
- Combine with :doc:`responses` to create custom responses.

**See also:** :doc:`responses`, :doc:`headers`, :doc:`../usage/use-cases`
