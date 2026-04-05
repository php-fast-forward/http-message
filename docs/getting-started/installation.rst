Installation
============

Install the package with Composer:

.. code-block:: bash

   composer require fast-forward/http-message

Requirements
------------

.. list-table:: Requirements
   :header-rows: 1

   * - Requirement
     - Version
     - Why it matters
   * - PHP
     - ``^8.3``
     - The library relies on strict typing, enums, and readonly objects.
   * - Composer
     - Current stable release
     - Used to install the package and its runtime dependencies.

Installed Runtime Dependencies
------------------------------

Composer will also install the dependencies declared by this package:

- ``nyholm/psr7`` as the underlying PSR-7 implementation.
- ``psr/http-message`` for the PSR-7 interfaces.

What Is Not Included
--------------------

This package intentionally does not include:

- PSR-17 factories;
- a PSR-11 service provider;
- a full HTTP client or server framework.

If you need factories or container integration, pair it with
`fast-forward/http-factory <https://github.com/php-fast-forward/http-factory>`_.

Next Step
---------

Continue with :doc:`quickstart` for a minimal JSON response example and a quick overview
of the most common response classes.
