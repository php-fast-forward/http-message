Redirect Response
=================

``RedirectResponse`` helps you build redirect responses without manually setting the
``Location`` header every time.

Default Behavior
----------------

- ``new RedirectResponse('/path')`` returns HTTP 302.
- ``new RedirectResponse('/path', permanent: true)`` returns HTTP 301.
- the ``Location`` header is always set from the provided URI or string.

Temporary and Permanent Redirects
---------------------------------

.. code-block:: php

   use FastForward\Http\Message\RedirectResponse;

   $temporary = new RedirectResponse('/login');
   $permanent = new RedirectResponse('/new-home', permanent: true);

Relative and Absolute Locations
-------------------------------

Both strings and ``UriInterface`` instances are accepted:

.. code-block:: php

   use FastForward\Http\Message\RedirectResponse;
   use Nyholm\Psr7\Uri;

   $relative = new RedirectResponse(new Uri('/profile'));
   $absolute = new RedirectResponse('https://example.com/profile');

Using Other Redirect Status Codes
---------------------------------

If you need ``303 See Other``, ``307 Temporary Redirect``, or ``308 Permanent Redirect``,
create the response first and then refine the status:

.. code-block:: php

   use FastForward\Http\Message\RedirectResponse;
   use FastForward\Http\Message\StatusCode;

   $response = (new RedirectResponse('/orders/42'))
       ->withStatus(StatusCode::SeeOther->value);

Beginner Tip
------------

Use the constructor for the common 301 and 302 cases.
Use ``withStatus()`` only when you intentionally need a less common redirect semantic.
