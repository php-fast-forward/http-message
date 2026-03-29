FAQ
===

**Q: What PHP version is required?**
A: PHP 8.3 or higher is required.

**Q: Is this library PSR-7 compliant?**
A: Yes, all responses and streams are fully PSR-7 compliant and extend Nyholm's implementation.

**Q: How do I create a JSON response?**
A: Use `JsonResponse` and pass your payload to the constructor. The `Content-Type` header is set automatically.

**Q: How do I work with payloads?**
A: Use `getPayload()` to retrieve the payload and `withPayload($payload)` to create a new instance with a different payload.

**Q: Can I use this with any framework?**
A: Yes, as long as your framework supports PSR-7 messages.

**Q: How do I handle authentication headers?**
A: Use the `Authorization` header utility and credential classes for parsing and handling authentication schemes.
