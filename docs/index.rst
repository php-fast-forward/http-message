Fast Forward HTTP Message
=========================

Fast Forward HTTP Message is a focused companion library for PSR-7 applications.
It builds on top of ``nyholm/psr7`` and adds practical response classes, payload-aware
JSON helpers, and small HTTP utility enums that remove repetitive string handling
from application code.

This package is a good fit when you want to:

- return JSON, HTML, text, empty, or redirect responses quickly;
- keep JSON payloads accessible after they have been encoded into a body stream;
- centralize HTTP header parsing and negotiation logic;
- use typed HTTP helpers without replacing your existing PSR-7 stack.

Useful Links
------------

- `GitHub Repository <https://github.com/php-fast-forward/http-message>`_
- `Packagist <https://packagist.org/packages/fast-forward/http-message>`_
- `Issue Tracker <https://github.com/php-fast-forward/http-message/issues>`_
- `Coverage Report <https://php-fast-forward.github.io/http-message/coverage/>`_

Documentation Map
-----------------

- :doc:`getting-started/index` for installation and a first working example.
- :doc:`usage/index` for scenario-based guides and response-specific pages.
- :doc:`advanced/index` for integration, customization, and troubleshooting.
- :doc:`api/index` for the public API, interfaces, enums, and helpers.
- :doc:`links/index` for project links, dependencies, standards, and coverage.

.. toctree::
   :maxdepth: 2
   :caption: Contents:

   getting-started/index
   usage/index
   advanced/index
   api/index
   links/index
   faq
   compatibility
