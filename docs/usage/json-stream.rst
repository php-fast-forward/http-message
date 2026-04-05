JSON Stream
===========

``JsonStream`` is the payload-aware stream used internally by ``JsonResponse``, but it can also
be used directly when you want a JSON body without creating a response object yet.

Why Use It Directly
-------------------

Direct ``JsonStream`` usage is helpful when:

- you are building your own response class;
- you need a PSR-7 stream before the final response exists;
- you want to preserve a decoded payload alongside the encoded body.

Basic Example
-------------

.. code-block:: php

   use FastForward\Http\Message\JsonStream;

   $stream = new JsonStream([
       'name' => 'Ada',
   ]);

   echo (string) $stream;           // {"name":"Ada"}
   echo $stream->getPayload()['name']; // Ada

Immutably Replacing the Payload
-------------------------------

.. code-block:: php

   $original = new JsonStream(['step' => 1]);
   $updated  = $original->withPayload(['step' => 2]);

Both streams remain valid and independent.

Encoding Options
----------------

``JsonStream`` uses ``JsonStream::ENCODING_OPTIONS`` by default. Those options include:

- ``JSON_THROW_ON_ERROR`` for predictable error handling;
- ``JSON_UNESCAPED_SLASHES`` for cleaner output;
- ``JSON_UNESCAPED_UNICODE`` for readable Unicode characters.

Failure Modes
-------------

``JsonStream`` will fail early instead of hiding encoding problems:

- it throws ``InvalidArgumentException`` when the payload contains a resource;
- it throws ``JsonException`` when JSON encoding fails, for example with invalid UTF-8 data.

This behavior is usually a feature because it surfaces bad payloads during development instead of sending broken output.
