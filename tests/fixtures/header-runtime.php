<?php

declare(strict_types=1);

use FastForward\Http\Message\Header\Accept;
use FastForward\Http\Message\Header\Authorization;
use FastForward\Http\Message\Header\Authorization\AwsCredential;
use FastForward\Http\Message\Header\Authorization\DigestCredential;
use FastForward\Http\Message\Header\ContentEncoding;
use FastForward\Http\Message\Header\ContentType;
use FastForward\Http\Message\Header\TransferEncoding;

// Deliberately do not load dev dependencies: their polyfills can hide runtime bugs.
// An optional Composer --no-dev autoloader can also be checked with this fixture.
// Example on PHP 8.3: php tests/fixtures/header-runtime.php ./vendor/autoload.php
// For a dependency-free source check: php -n tests/fixtures/header-runtime.php
if (isset($argv[1])) {
    require $argv[1];
} else {
    spl_autoload_register(static function (string $class): void {
        $prefix = 'FastForward\\Http\\Message\\';
        if (str_starts_with($class, $prefix)) {
            require dirname(__DIR__, 2) . '/src/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
        }
    });
}

$checks = [
    'Accept' => static fn (): bool => Accept::ApplicationJson === Accept::getBestMatch(" \tapplication/json\t ", [Accept::ApplicationJson]),
    'ContentType' => static fn (): bool => 'UTF-8' === ContentType::getCharset("text/plain; charset= \tUTF-8\t "),
    'ContentEncoding' => static fn (): bool => ContentEncoding::isSupported(ContentEncoding::Gzip, 'GZIP;q=1')
        && ! ContentEncoding::isSupported(ContentEncoding::Gzip, 'GZIP;q=0'),
    'TransferEncoding' => static fn (): bool => TransferEncoding::isChunked("GZIP, \tCHUNKED\t "),
    'Digest' => static function (): bool {
        $value = "\u{00A0}Élodie\xFF\u{00A0}";
        $credential = Authorization::parse('Digest USERNAME="' . $value . '", realm="Example", nonce="ABC", uri="/Path", response="ABC", qop="auth", nc="00000001", cnonce="XYZ"');
        return $credential instanceof DigestCredential && $value === $credential->username
            && 'Example' === $credential->realm && 'ABC' === $credential->nonce && '/Path' === $credential->uri;
    },
    'AWS' => static function (): bool {
        $scope = "KEY\u{00A0}/20261021/Region/Service/aws4_request";
        $signature = str_repeat('Ab', 32);
        $credential = Authorization::parse('AWS4-HMAC-SHA256 Credential=' . $scope . ', SignedHeaders=host;x-test, Signature=' . $signature);
        return $credential instanceof AwsCredential && $scope === $credential->credentialScope
            && $signature === $credential->signature && 'host;x-test' === $credential->signedHeaders;
    },
    'Unicode is not OWS' => static fn (): bool => null === Accept::getBestMatch("\u{00A0}application/json\u{00A0}", [Accept::ApplicationJson])
        && ! TransferEncoding::isChunked("\u{00A0}chunked\u{00A0}")
        && "\u{00A0}UTF-8\u{00A0}" === ContentType::getCharset("text/plain; charset=\u{00A0}UTF-8\u{00A0}"),
];

$failed = false;
foreach ($checks as $name => $check) {
    try {
        if (! $check()) {
            throw new RuntimeException('Unexpected result');
        }
        echo $name . ": ok\n";
    } catch (Throwable $error) {
        // Fixtures contain synthetic data only; never print credential values.
        fwrite(STDERR, $name . ': ' . $error->getMessage() . "\n");
        $failed = true;
    }
}
exit($failed ? 1 : 0);
