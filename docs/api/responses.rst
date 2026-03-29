Responses
=========

Fast Forward HTTP Message provides several response classes, all immutable and extending Nyholm's PSR-7 Response implementation:

.. list-table:: Response Classes
   :header-rows: 1

   * - Class
     - Description
     - Usage Example
   * - ``JsonResponse``
     - JSON response with automatic headers
     - Instantiated with an array or object as payload. Automatically sets Content-Type to application/json and serializes the payload.
   * - ``TextResponse``
     - Plain text response
     - Instantiated with a string. Sets Content-Type to text/plain.
   * - ``HtmlResponse``
     - HTML response with correct Content-Type
     - Instantiated with a string containing HTML. Sets Content-Type to text/html.
   * - ``EmptyResponse``
     - HTTP 204 No Content response
     - Instantiated with no arguments. Sets status code 204 and an empty body.
   * - ``RedirectResponse``
     - HTTP redirect response (301/302)
     - Instantiated with a target URL and an optional boolean for permanent (301) or temporary (302) redirect. Sets Location header.

All response classes are strictly typed and designed for extension.

**Examples:**

.. code-block:: php

    // JSON response
    use FastForward\Http\Message\JsonResponse;
    $response = new JsonResponse(['foo' => 'bar']);
    echo $response->getHeaderLine('Content-Type'); // application/json; charset=utf-8
    echo (string) $response->getBody(); // {"foo":"bar"}

    // Text response
    use FastForward\Http\Message\TextResponse;
    $text = new TextResponse('Hello, world!');

    // Empty response
    use FastForward\Http\Message\EmptyResponse;
    $empty = new EmptyResponse();

    // Redirect response (permanent)
    use FastForward\Http\Message\RedirectResponse;
    $redirect = new RedirectResponse('https://example.com', true); // 301

**Advanced Tips:**

- You can extend any response class to add custom logic for your application.
- All responses support PSR-7 methods like ``withHeader()``, ``withStatus()``, and are safe for concurrent use.
- For payload manipulation, see :doc:`payload`.

**See also:** :doc:`../usage/use-cases`, :doc:`payload`, :doc:`headers`
