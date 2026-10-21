<?php

declare(strict_types=1);

require $argv[1] ?? dirname(__DIR__, 4) . '/vendor/autoload.php';

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
try {
    new JsonStream("\xB1");
    throw new RuntimeException('Invalid UTF-8 unexpectedly encoded.');
} catch (JsonException) {
}

echo "fast-forward-http-message: ok\n";
