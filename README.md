# Fast Forward HTTP Message

[![PHP Version](https://img.shields.io/badge/php-^8.3-blue.svg)](https://www.php.net/releases/)
[![License](https://img.shields.io/github/license/php-fast-forward/http-message.svg)](https://opensource.org/licenses/MIT)
[![Tests](https://github.com/php-fast-forward/http-message/actions/workflows/tests.yml/badge.svg)](https://github.com/php-fast-forward/http-message/actions)
[![Coverage](https://img.shields.io/badge/coverage-html-green?logo=phpunit&label=coverage&style=flat)](https://php-fast-forward.github.io/http-message/coverage/)
[![Downloads](https://img.shields.io/packagist/dt/fast-forward/http-message)](https://packagist.org/packages/fast-forward/http-message)

A modern PHP library for working with PSR-7 HTTP Messages, focusing on immutability, strict typing, and developer ergonomics. Part of the [Fast Forward](https://github.com/php-fast-forward) ecosystem.

---

## ✨ Features
- ✅ Fully PSR-7 compliant
- ✅ Strictly typed for PHP 8.3+
- ✅ Immutable by design (PSR-7 standard)
- ✅ Convenient JSON, HTML, Text, Empty, and Redirect responses
- ✅ Payload-aware interfaces for reusable patterns
- ✅ No external dependencies beyond PSR-7

---

## 📦 Installation

```bash
composer require fast-forward/http-message
```
Requirements: PHP 8.3+, Composer

---

## 🛠️ Usage

### JSON Response Example

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

### Text Response Example
```php
use FastForward\Http\Message\TextResponse;
$text = new TextResponse('Hello, world!');
```

### Empty Response Example
```php
use FastForward\Http\Message\EmptyResponse;
$empty = new EmptyResponse();
```

### Redirect Response Example
```php
use FastForward\Http\Message\RedirectResponse;
$redirect = new RedirectResponse('https://example.com', true); // Permanent redirect
```

---

## 🧰 API Summary
| Class/Method         | Description                                      | Docs |
|----------------------|--------------------------------------------------|------|
| [JsonResponse](docs/api/responses.rst)         | JSON response with automatic headers              | [docs](docs/api/responses.rst) |
| [TextResponse](docs/api/responses.rst)         | Plain text response                              | [docs](docs/api/responses.rst) |
| [HtmlResponse](docs/api/responses.rst)         | HTML response with correct Content-Type           | [docs](docs/api/responses.rst) |
| [EmptyResponse](docs/api/responses.rst)        | HTTP 204 No Content response                     | [docs](docs/api/responses.rst) |
| [RedirectResponse](docs/api/responses.rst)     | HTTP redirect response (301/302)                 | [docs](docs/api/responses.rst) |
| [getPayload()](docs/api/payload.rst)           | Returns the payload of the response              | [docs](docs/api/payload.rst) |
| [withPayload($data)](docs/api/payload.rst)     | Returns a new instance with a different payload  | [docs](docs/api/payload.rst) |

---

## 🔌 Integration
- Works out of the box with any PSR-7 compatible framework or library
- Designed to extend and complement [`nyholm/psr7`](https://github.com/Nyholm/psr7)
- Can be used with [fast-forward/container](https://github.com/php-fast-forward/container) for dependency injection

---

## 📁 Directory Structure Example
```
src/
├── EmptyResponse.php
├── HtmlResponse.php
├── JsonResponse.php
├── TextResponse.php
├── RedirectResponse.php
├── ...
```

---

## ⚙️ Advanced/Customization
- Extend any response class to add custom logic
- Compose with other PSR-7 middlewares or response decorators
- All interfaces are public and designed for extension

---

## 🛠️ Versioning & Breaking Changes
- v1.4: PHP 8.3+ required, stricter typing, improved docs
- v1.0: Initial release

---

## ❓ FAQ
**Q:** What PHP version is required?  
**A:** PHP 8.3 or higher.

**Q:** Is this library PSR-7 compliant?  
**A:** Yes, all responses and streams are fully PSR-7 compliant and extend Nyholm's implementation.

**Q:** How do I create a JSON response?  
**A:** Use `JsonResponse` and pass your payload to the constructor. The `Content-Type` header is set automatically.

**Q:** How do I work with payloads?  
**A:** Use `getPayload()` to retrieve the payload and `withPayload($payload)` to create a new instance with a different payload.

**Q:** Can I use this with any framework?  
**A:** Yes, as long as your framework supports PSR-7 messages.

**Q:** How do I handle authentication headers?  
**A:** Use the `Authorization` header utility and credential classes for parsing and handling authentication schemes.

---

## 🛡 License
MIT © 2026 [Felipe Sayão Lobato Abreu](https://github.com/mentordosnerds)

---

## 🤝 Contributing
Contributions, issues, and feature requests are welcome!  
Feel free to open a [GitHub Issue](https://github.com/php-fast-forward/http-message/issues) or submit a Pull Request.

---

## 🔗 Links
- [Repository](https://github.com/php-fast-forward/http-message)
- [Packagist](https://packagist.org/packages/fast-forward/http-message)
- [Sphinx Documentation](docs/index.rst)
- [RFC 2119](https://datatracker.ietf.org/doc/html/rfc2119)
- [PSR-7](https://www.php-fig.org/psr/psr-7/)
- [PSR-15](https://www.php-fig.org/psr/psr-15/)
- [nyholm/psr7](https://github.com/Nyholm/psr7)
