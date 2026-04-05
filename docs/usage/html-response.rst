HTML Response
=============

``HtmlResponse`` is the simplest way to return HTML while keeping PSR-7 compatibility.

What It Does
------------

- sets the response status to HTTP 200;
- sets ``Content-Type`` to ``text/html`` with a charset;
- writes the provided HTML string into the body.

Basic Example
-------------

.. code-block:: php

   use FastForward\Http\Message\HtmlResponse;

   $response = new HtmlResponse('<h1>Hello</h1>');

   echo $response->getHeaderLine('Content-Type'); // text/html; charset=utf-8

Custom Charset
--------------

.. code-block:: php

   $response = new HtmlResponse(
       html: '<p>Ol&aacute;</p>',
       charset: 'iso-8859-1',
   );

Additional Headers
------------------

.. code-block:: php

   $response = new HtmlResponse(
       '<h1>Dashboard</h1>',
       headers: ['Cache-Control' => 'no-store'],
   );

When to Choose HTML Over JSON
-----------------------------

Use ``HtmlResponse`` when the client is expected to render markup directly, such as:

- a small PSR-15 web endpoint;
- a maintenance page;
- a fallback view for browser requests.

If you need to choose dynamically between HTML and JSON, see :doc:`header-utilities`
for ``Accept`` negotiation.
