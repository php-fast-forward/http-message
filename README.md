# Fast Forward HTTP Message

[![License](https://img.shields.io/github/license/php-fast-forward/http-message.svg)](https://opensource.org/licenses/MIT)
[![Tests](https://github.com/php-fast-forward/http-message/actions/workflows/tests.yml/badge.svg)](https://github.com/php-fast-forward/http-message/actions)

## Overview

The **Fast Forward HTTP Message** library provides utility classes and implementations for working with PSR-7 HTTP Messages in PHP, with a strong focus on immutability, strict typing, and developer ergonomics.

This library is designed to extend and complement [`nyholm/psr7`](https://github.com/Nyholm/psr7), providing additional structures for managing payloads, including convenient support for JSON and other payload-centric responses.

---

## Features

✅ Fully PSR-7 compliant  
✅ Strictly typed for PHP 8.2+  
✅ Immutable by design (PSR-7 standard)  
✅ Convenient JSON response with automatic headers  
✅ Payload-aware interfaces for reusable patterns  
✅ No external dependencies beyond PSR-7

---

## Installation

This package requires **PHP 8.2 or higher** and can be installed via Composer:

```bash
composer require fast-forward/http-message
```

## Usage

### Json Response Example

```php
use FastForward\Http\Message\JsonResponse;

$response = new JsonResponse(['success' => true]);

echo $response->getStatusCode(); // 200
echo $response->getHeaderLine('Content-Type'); // application/json; charset=utf-8

echo (string) $response->getBody(); // {"success":true}
```

### Payload Access and Immutability

```php
$newResponse = $response->withPayload(['success' => false]);

echo $response->getPayload()['success']; // true
echo $newResponse->getPayload()['success']; // false
```

## 🛡 License

This package is open-source software licensed under the [MIT License](https://opensource.org/licenses/MIT).

---

## 🤝 Contributing

Contributions, issues, and feature requests are welcome!  
Feel free to open a [GitHub Issue](https://github.com/php-fast-forward/http-message/issues) or submit a Pull Request.
