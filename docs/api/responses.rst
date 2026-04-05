Responses
=========

All built-in response classes extend ``Nyholm\Psr7\Response`` and remain fully compatible
with PSR-7 consumers. They are immutable, strictly typed, and designed to remove repetitive
response setup from application code.

.. important::

   The built-in response classes are ``final``. If you need custom behavior, prefer composition
   or implement your own response on top of :doc:`payload` primitives. See :doc:`../advanced/customization`.

.. list-table:: Built-In Response Classes
   :header-rows: 1

   * - Class
     - Default Status
     - Automatic Headers
     - Body Type
     - Best Used For
   * - ``JsonResponse``
     - ``200``
     - ``Content-Type: application/json; charset=...``
     - ``JsonStream``
     - APIs and any response that should keep a structured payload available.
   * - ``HtmlResponse``
     - ``200``
     - ``Content-Type: text/html; charset=...``
     - Standard PSR-7 stream
     - Markup rendered directly by browsers or simple web views.
   * - ``TextResponse``
     - ``200``
     - ``Content-Type: text/plain; charset=...``
     - Standard PSR-7 stream
     - Diagnostics, simple text endpoints, and human-readable plain output.
   * - ``EmptyResponse``
     - ``204``
     - None
     - Empty body
     - Successful operations where returning a body would add no value.
   * - ``RedirectResponse``
     - ``302`` or ``301``
     - ``Location``
     - Empty body by default
     - Redirect flows such as login redirects or resource moves.

How to Choose the Right Response
--------------------------------

- Use ``JsonResponse`` when clients expect machine-readable payloads.
- Use ``HtmlResponse`` when clients will render HTML.
- Use ``TextResponse`` when the payload is naturally just a string.
- Use ``EmptyResponse`` when success is enough and the body should stay empty.
- Use ``RedirectResponse`` when the next step is another URI.

Common PSR-7 Operations Still Work
----------------------------------

The convenience classes do not hide the underlying PSR-7 API:

.. code-block:: php

   use FastForward\Http\Message\JsonResponse;
   use FastForward\Http\Message\StatusCode;

   $response = (new JsonResponse(['saved' => true]))
       ->withStatus(StatusCode::Created->value)
       ->withHeader('X-Request-Id', 'req-123');

Response-Specific Guides
------------------------

- :doc:`../usage/json-response`
- :doc:`../usage/html-response`
- :doc:`../usage/text-response`
- :doc:`../usage/empty-response`
- :doc:`../usage/redirect-response`
