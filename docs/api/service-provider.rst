Service Provider
===============

This package does not provide a service provider or dependency injection integration by default. All classes are designed for direct instantiation and use.

**For container integration and PSR-17 factories, use** `fast-forward/http-factory <https://github.com/php-fast-forward/http-factory>`_.
It provides ready-to-use service providers and factories for PSR-7/PSR-17, compatible with this package and the Fast Forward ecosystem.

If you wish to integrate manually, simply register the classes as services as needed.