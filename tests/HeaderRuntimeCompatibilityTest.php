<?php

declare(strict_types=1);

namespace FastForward\Http\Message\Tests;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;

#[CoversNothing]
final class HeaderRuntimeCompatibilityTest extends TestCase
{
    /**
     * Verifies header parsing in an isolated production-style PHP process.
     *
     * @return void
     */
    public function testHeadersWithoutDevelopmentPolyfillsOrMbstringFunctions(): void
    {
        $process = proc_open([
            PHP_BINARY,
            '-d',
            'disable_functions=mb_trim,mb_strtolower',
            __DIR__ . '/fixtures/header-runtime.php',
        ], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        self::assertIsResource($process);

        $output = stream_get_contents($pipes[1]);
        $errors = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        self::assertSame(0, proc_close($process), $errors . $output);
        self::assertStringContainsString('AWS: ok', $output);
    }
}
