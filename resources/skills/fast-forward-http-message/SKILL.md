---
name: fast-forward-http-message
description: Use Fast Forward PSR-7 payload responses, JSON streams, status and header helpers, preserving immutability and propagating encoding failures.
license: MIT; see LICENSE
compatibility: Supports fast-forward/http-message ^1.5 and PHP ^8.3. Requires Composer and declared extensions; inspect the installed version. Earlier releases are outside this contract.
metadata:
  author: Fast Forward
  api-baseline: fast-forward/http-message ^1.5
---

# Build immutable HTTP responses

Use this skill for an application consuming `fast-forward/http-message:^1.5`.
Read its instructions, manifest/lockfile and installed source before editing.
Run `composer show fast-forward/http-message`. For versions outside this contract,
inspect matching documentation; do not silently upgrade the application.

## First working integration

Requires PHP ^8.3, Composer's application autoloader and this package's runtime
dependencies.
With authority to install the dependency:

```sh
composer require 'fast-forward/http-message:^1.5'
```

Create `app.php` in the application root alongside composer.json/vendor:

```php
<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use FastForward\Http\Message\JsonResponse;
use FastForward\Http\Message\JsonStream;
use FastForward\Http\Message\RedirectResponse;
use FastForward\Http\Message\Header\Authorization;

$original = new JsonResponse(['ready' => false]);
$changed = $original->withPayload(['ready' => true])->withStatus(201);
if ($original->getPayload() !== ['ready' => false] || $changed->getPayload() !== ['ready' => true]
    || $changed->getStatusCode() !== 201 || !str_starts_with($changed->getHeaderLine('Content-Type'), 'application/json')
    || json_decode((string) $changed->getBody(), true, flags: JSON_THROW_ON_ERROR) !== ['ready' => true]
    || (new RedirectResponse('/login'))->getStatusCode() !== 302
    || Authorization::parse('unsupported fixture') !== null) {
    throw new RuntimeException('Response contract differs.');
}

echo "integration ready\n";
```

Run `php app.php`. It throws on a wrong result; success prints
`integration ready`. It uses no production data and starts no server.

## Wiring and failure boundaries

This library uses `FastForward\Http\Message`; no container/provider is required. Construct JsonResponse with an application payload, use withStatus/withHeader/withPayload and retain the returned object. The application server or emitter must write the response; constructing it sends nothing.

JsonResponse sets an application/json Content-Type with charset and uses JsonStream. withPayload returns a new stream/response, but PSR-7 streams can themselves be mutable: do not treat a shared stream's cursor or write() as an immutable payload update. Top-level resources raise InvalidArgumentException; malformed UTF-8 and other JSON encoding failures raise JsonException. Keep the original response when testing mutation boundaries.

TextResponse and HtmlResponse set their corresponding content types, EmptyResponse is 204, RedirectResponse uses 302 or 301 for its permanent flag. RequestMethod/StatusCode and Header helpers are independent utilities. Authorization::parse returns null for missing/unknown or malformed supported credentials; parsing is not authentication. Never log credentials. Digest/AWS parsing uses mb_trim, which is native only from PHP 8.4: on PHP 8.3 install an appropriate polyfill before using those optional routes. The core JSON example needs no such route.

## Verify integration

Run the [complete example](examples/integration.php) against the application's
autoloader in a disposable process. It checks success and negative cases:

```sh
php /path/to/fast-forward-http-message/examples/integration.php ./vendor/autoload.php
```

Expected final marker: `fast-forward-http-message: ok`. Keep application-specific regression
tests in its existing PHPUnit suite, with each fixture class in its same-named
file. File copying, host discovery and completed agent tasks are different checks.
For optional copying/synchronization load
[installation and lifecycle](references/installation.md).
