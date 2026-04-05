Dependencies
============

.. list-table:: Runtime Dependencies
   :header-rows: 1

   * - Package
     - Why It Is Used
     - Notes
   * - `nyholm/psr7 <https://github.com/Nyholm/psr7>`_
     - Base PSR-7 implementation for built-in responses and streams.
     - ``JsonResponse`` and the other convenience responses extend Nyholm classes.
   * - `psr/http-message <https://www.php-fig.org/psr/psr-7/>`_
     - PSR-7 interfaces.
     - Ensures interoperability with the broader PHP HTTP ecosystem.

.. list-table:: Development Dependency
   :header-rows: 1

   * - Package
     - Why It Is Used
   * - ``fast-forward/dev-tools``
     - Development tooling used by contributors for code quality and project automation.

Optional Companion Dependency
-----------------------------

The following package is not required at runtime, but is commonly useful alongside this library:

- `fast-forward/http-factory <https://github.com/php-fast-forward/http-factory>`_ for PSR-17 factories and service-provider style integration.
