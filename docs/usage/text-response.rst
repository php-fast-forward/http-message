Text Response
=============

``TextResponse`` returns plain text with the correct ``Content-Type`` header.
It is useful for lightweight endpoints, diagnostics, health checks, or command-style HTTP output.

Basic Example
-------------

.. code-block:: php

   use FastForward\Http\Message\TextResponse;

   $response = new TextResponse('Service is healthy.');

   echo $response->getHeaderLine('Content-Type'); // text/plain; charset=utf-8

Custom Charset
--------------

.. code-block:: php

   $response = new TextResponse(
       text: 'Plain text in another encoding',
       charset: 'iso-8859-1',
   );

Additional Headers
------------------

.. code-block:: php

   $response = new TextResponse(
       'Rate limit reached.',
       headers: ['Retry-After' => '60'],
   );

When to Prefer Text
-------------------

Choose ``TextResponse`` when:

- the payload is naturally a single string;
- a human, not another program, is the main reader;
- JSON would add unnecessary ceremony.
