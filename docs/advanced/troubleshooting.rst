Troubleshooting
===============

This page covers the most common surprises developers run into when they first integrate the package.

JSON Encoding Fails
-------------------

Symptoms:

- ``JsonException`` while creating ``JsonStream`` or ``JsonResponse``;
- ``InvalidArgumentException`` when a payload contains a resource.

What to check:

- make sure the payload contains only JSON-encodable values;
- remove resource handles such as file pointers;
- validate string encoding if the payload may contain invalid UTF-8.

Authorization Parsing Returns Null
----------------------------------

``Authorization::parse()``, ``fromHeaderCollection()``, and ``fromRequest()`` return ``null`` when:

- the header is missing;
- the scheme is unsupported;
- the header format is malformed;
- required fields for Digest or AWS credentials are missing.

Treat ``null`` as "nothing usable was parsed" and branch accordingly.

No Accept Match Is Found
------------------------

``Accept::getBestMatch()`` returns ``null`` when none of your supported types match the client header.
In that case, you usually have two choices:

- return a fallback format intentionally;
- respond with HTTP 406 if your application wants strict negotiation.

The Built-In Responses Are Final
--------------------------------

If inheritance fails, that is expected. The built-in convenience classes are ``final``.
Use :doc:`customization` to build your own response class on top of ``JsonStream`` or plain PSR-7 responses.

Compression Checks Look Too Permissive
--------------------------------------

``ContentEncoding::isSupported()`` follows HTTP negotiation semantics:

- omitted ``Accept-Encoding`` headers are treated as implicit acceptance;
- wildcard entries may accept encodings that are not listed explicitly;
- ``q=0`` means explicit rejection.

If that feels surprising, it is usually the HTTP rule, not a bug in the helper.

Redirect Targets Are Not Validated for You
------------------------------------------

``RedirectResponse`` sets the ``Location`` header from the string or URI you provide.
Your application is still responsible for deciding whether that target is safe, absolute, relative, internal, or allowed.

Where to Look Next
------------------

- :doc:`../usage/json-stream`
- :doc:`../usage/header-utilities`
- :doc:`customization`
