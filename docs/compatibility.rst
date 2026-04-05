Compatibility
=============

Fast Forward HTTP Message is intentionally small and framework-agnostic.
Its compatibility surface is easy to reason about:

.. list-table:: Runtime Compatibility
   :header-rows: 1

   * - Concern
     - Supported Version
     - Notes
   * - PHP
     - ``^8.3``
     - The package uses modern PHP 8.3 features such as enums, readonly classes, and strict typing.
   * - PSR HTTP Messages
     - ``psr/http-message ^2.0``
     - All responses and streams remain interoperable with PSR-7 consumers.
   * - Base implementation
     - ``nyholm/psr7 ^1.8``
     - Built-in response and stream classes extend Nyholm's implementation.

Interoperability Notes
----------------------

- The package does not lock you into a specific framework.
- The package does not ship PSR-17 factories or a PSR-11 service provider.
- The package works well inside PSR-15 middleware and request handlers because it returns normal PSR-7 objects.

If you also need container bindings or factories, see :doc:`api/service-provider`
and :doc:`advanced/integration`.
