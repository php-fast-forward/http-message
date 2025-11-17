<?php

declare(strict_types=1);

namespace FastForward\Http\Message\Header;

use function explode;
use function str_contains;
use function str_ends_with;
use function str_starts_with;
use function strtok;
use function trim;
use function usort;

/**
 * Defines common HTTP Accept header values and provides content negotiation helpers.
 */
enum Accept: string
{
    // Common Types
    case ApplicationJson = 'application/json';
    case ApplicationXml = 'application/xml';
    case TextHtml = 'text/html';
    case TextPlain = 'text/plain';

    /**
     * Parses an HTTP Accept header and returns the best match from a list of server-supported types.
     *
     * This method handles quality factors (q-values) and specificity rules, where a more specific
     * type (e.g., 'application/json') is preferred over a less specific one (e.g., 'application/*' or '* / *')
     * if their quality factors are equal.
     *
     * @param string $acceptHeader The raw string from the HTTP Accept header.
     * @param self[] $supportedTypes An array of `Accept` cases that the server supports.
     * @return self|null The best matching `Accept` case, or null if no suitable match is found.
     */
    public static function getBestMatch(string $acceptHeader, array $supportedTypes): ?self
    {
        $clientPreferences = self::parseHeader($acceptHeader);

        foreach ($clientPreferences as $preference) {
            foreach ($supportedTypes as $supported) {
                if (self::matches($preference['type'], $supported->value)) {
                    return $supported;
                }
            }
        }

        return null;
    }

    /**
     * Parses the Accept header string into a sorted list of preferences.
     *
     * @return array<int, array{type: string, q: float, specificity: int}>
     */
    private static function parseHeader(string $header): array
    {
        $preferences = [];
        $parts = explode(',', $header);

        foreach ($parts as $part) {
            $params = explode(';', $part);
            $type = trim($params[0]);
            $q = 1.0;
            
            if (isset($params[1]) && str_contains($params[1], 'q=')) {
                $q = (float) trim(explode('=', $params[1])[1]);
            }

            $preferences[] = [
                'type' => $type,
                'q' => $q,
                'specificity' => self::calculateSpecificity($type),
            ];
        }

        usort($preferences, function ($a, $b) {
            if ($a['q'] !== $b['q']) {
                return $b['q'] <=> $a['q']; // Sort by quality factor descending
            }
            if ($a['specificity'] !== $b['specificity']) {
                return $b['specificity'] <=> $a['specificity']; // Then by specificity descending
            }
            return 0;
        });

        return $preferences;
    }

    /**
     * Calculates the specificity of a MIME type.
     */
    private static function calculateSpecificity(string $type): int
    {
        if ($type === '*/*') {
            return 0;
        }
        if (str_ends_with($type, '/*')) {
            return 1;
        }
        return 2;
    }

    /**
     * Checks if a client-preferred type matches a server-supported type.
     */
    private static function matches(string $preference, string $supported): bool
    {
        if ($preference === '*/*' || $preference === $supported) {
            return true;
        }

        if (str_ends_with($preference, '/*')) {
            $prefix = strtok($preference, '/');
            if (str_starts_with($supported, $prefix . '/')) {
                return true;
            }
        }

        return false;
    }
}
