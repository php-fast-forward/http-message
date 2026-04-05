FAQ
===

What PHP version do I need?
---------------------------

You need PHP 8.3 or newer. See :doc:`compatibility` for the full runtime matrix.

Is this a full PSR-7 implementation?
------------------------------------

Not by itself. This package builds on ``nyholm/psr7`` and adds convenience responses, payload-aware JSON handling,
and typed HTTP helpers on top of a PSR-7 implementation.

Which response should I start with?
-----------------------------------

If you are building an API, start with :doc:`usage/json-response`.
If you are returning browser markup, start with :doc:`usage/html-response`.
If you need no body at all, use :doc:`usage/empty-response`.

How do I return HTTP 201, 202, or another non-default status?
--------------------------------------------------------------

Create the response you want first, then call ``withStatus()``:

.. code-block:: php

   use FastForward\Http\Message\JsonResponse;
   use FastForward\Http\Message\StatusCode;

   $response = (new JsonResponse(['saved' => true]))
       ->withStatus(StatusCode::Created->value);

How do I change a JSON payload after creating the response?
-----------------------------------------------------------

Use ``withPayload()``. It returns a new response instance and leaves the original one unchanged.
See :doc:`usage/json-response`.

What is the difference between JsonResponse and JsonStream?
-----------------------------------------------------------

``JsonResponse`` is a full PSR-7 response object.
``JsonStream`` is only the body stream. Use ``JsonStream`` when you need the JSON-encoded body without creating the full response yet.

What happens if my payload cannot be encoded as JSON?
-----------------------------------------------------

Encoding fails early. ``JsonStream`` and ``JsonResponse`` may throw ``JsonException`` or ``InvalidArgumentException``.
See :doc:`advanced/troubleshooting`.

Can I use this package with any framework?
------------------------------------------

Yes, as long as the framework works with PSR-7 objects. The package is intentionally framework-agnostic.

Does the package include a service provider or PSR-17 factories?
----------------------------------------------------------------

No. For that, use `fast-forward/http-factory <https://github.com/php-fast-forward/http-factory>`_.
See :doc:`api/service-provider`.

Can I extend the built-in response classes?
-------------------------------------------

No. The built-in response classes are ``final``. If you need custom behavior, use composition or build your own class with
``PayloadResponseInterface`` and ``JsonStream``. See :doc:`advanced/customization`.

How do I parse Authorization headers safely?
--------------------------------------------

Use the ``Authorization`` helpers documented in :doc:`usage/header-utilities`.
They return structured credential objects or ``null`` when parsing fails.

How do I decide whether to serve HTML or JSON?
----------------------------------------------

Use ``Accept::getBestMatch()`` with the media types your endpoint supports.
See :doc:`usage/header-utilities` and :doc:`usage/use-cases`.

How do I know whether a client accepts a compressed response?
-------------------------------------------------------------

Use ``ContentEncoding::isSupported()`` with the encoding you want to send and the raw ``Accept-Encoding`` header value.

Can RedirectResponse be used with relative URLs?
------------------------------------------------

Yes. ``RedirectResponse`` accepts both strings and ``UriInterface`` instances, including relative paths.
