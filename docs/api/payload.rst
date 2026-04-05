Payload Interfaces and Streams
==============================

Payload support is the feature that makes this package more than a collection of response shortcuts.
It lets structured data stay available after it has been encoded into a PSR-7 body stream.

.. list-table:: Payload Building Blocks
   :header-rows: 1

   * - Name
     - Kind
     - Used By
     - Purpose
   * - ``PayloadAwareInterface``
     - Interface
     - ``JsonResponse``, ``JsonStream``, custom implementations
     - Exposes ``getPayload()`` so callers can retrieve the structured payload.
   * - ``PayloadImmutableInterface``
     - Interface
     - Internal composition building block
     - Exposes ``withPayload()`` for immutable payload replacement.
   * - ``PayloadResponseInterface``
     - Interface
     - ``JsonResponse`` and custom responses
     - Combines payload access with ``ResponseInterface``.
   * - ``PayloadStreamInterface``
     - Interface
     - ``JsonStream`` and custom streams
     - Combines payload access with ``StreamInterface``.
   * - ``JsonStream``
     - Concrete class
     - ``JsonResponse`` and direct stream usage
     - Encodes JSON while preserving the original payload value.

Understanding the Interfaces
----------------------------

For everyday usage, most developers only need these two ideas:

- a payload-aware response implements ``PayloadResponseInterface``;
- a payload-aware stream implements ``PayloadStreamInterface``.

``PayloadImmutableInterface`` is public because it participates in those contracts, but it is mainly useful
when you build your own implementations.

Working with JsonResponse
-------------------------

.. code-block:: php

   use FastForward\Http\Message\JsonResponse;

   $response = new JsonResponse(['foo' => 'bar']);

   $payload = $response->getPayload();      // ['foo' => 'bar']
   $updated = $response->withPayload(['baz' => 123]);

Working with JsonStream
-----------------------

.. code-block:: php

   use FastForward\Http\Message\JsonStream;

   $stream = new JsonStream(['foo' => 'bar']);

   $json    = (string) $stream;             // {"foo":"bar"}
   $payload = $stream->getPayload();        // ['foo' => 'bar']

When This Matters
-----------------

Payload-aware responses and streams are useful when:

- middleware needs to inspect a JSON body without reparsing it;
- you want a custom response object that still exposes structured data;
- you care about immutability and do not want transport concerns to erase application data.

See also :doc:`responses`, :doc:`../usage/json-response`, and :doc:`../usage/json-stream`.
