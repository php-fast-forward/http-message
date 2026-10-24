<?php

declare(strict_types=1);

/**
 * This file is part of php-fast-forward/http-message.
 *
 * This source file is subject to the license bundled
 * with this source code in the file LICENSE.
 *
 * @copyright Copyright (c) 2025-2026 Felipe Sayão Lobato Abreu <github@mentordosnerds.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 *
 * @see       https://github.com/php-fast-forward/http-message
 * @see       https://github.com/php-fast-forward
 * @see       https://datatracker.ietf.org/doc/html/rfc2119
 */

namespace FastForward\Http\Message\Header;

/**
 * Enum Accept.
 *
 * Represents common HTTP Accept header values and provides robust helpers for
 * content negotiation in accordance with RFC 2119 requirement levels.
 * This enum MUST be used to ensure that Accept-type comparisons are performed
 * consistently and predictably. Implementations interacting with this enum
 * SHOULD rely on its parsing and negotiation logic to determine the most
 * appropriate response format based on client preferences.
 */
enum Accept: string
{
    // Common Types
    case ApplicationJson = 'application/json';
    case ApplicationXml  = 'application/xml';
    case TextHtml        = 'text/html';
    case TextPlain       = 'text/plain';

    /**
     * Determines the best matching content type from the client's Accept header.
     *
     * This method SHALL apply proper HTTP content negotiation rules, including:
     * - The most specific matching range determines each representation's quality.
     * - Zero-quality representations are excluded before ranking by quality.
     * - Specificity preference, where more explicit types MUST be preferred over
     *   wildcard types when q-values are equal.
     * If a match cannot be found in the list of supported server types,
     * the method MUST return null.
     *
     * @param string $acceptHeader the raw HTTP Accept header value
     * @param self[] $supportedTypes an array of enum cases the server supports
     *
     * @return self|null the best match, or null if no acceptable type exists
     */
    public static function getBestMatch(string $acceptHeader, array $supportedTypes): ?self
    {
        $clientPreferences = self::parseHeader($acceptHeader);

        $bestMatch = null;
        $bestQuality = 0.0;
        $bestSpecificity = -1;

        foreach ($supportedTypes as $supported) {
            $effective = self::findEffectivePreference($clientPreferences, $supported->value);

            if (null !== $effective && $effective['q'] > 0.0
                && ($effective['q'] > $bestQuality
                    || ($effective['q'] === $bestQuality && $effective['specificity'] > $bestSpecificity))) {
                $bestMatch = $supported;
                $bestQuality = $effective['q'];
                $bestSpecificity = $effective['specificity'];
            }
        }

        return $bestMatch;
    }

    /**
     * Finds the most specific applicable range before considering its quality.
     *
     * @param array<int, array{type: string, q: float, specificity: int}> $preferences
     *
     * @return array{type: string, q: float, specificity: int}|null
     */
    private static function findEffectivePreference(array $preferences, string $supported): ?array
    {
        $effective = null;
        foreach ($preferences as $preference) {
            if (self::matches($preference['type'], $supported)
                && (null === $effective || $preference['specificity'] > $effective['specificity'])) {
                $effective = $preference;
            }
        }

        return $effective;
    }

    /**
     * Parses media ranges in field order. These enum cases represent bare media
     * types, so ranges requiring media parameters do not match them.
     *
     * @return array<int, array{type: string, q: float, specificity: int}>
     */
    private static function parseHeader(string $header): array
    {
        $preferences = [];
        $ranges = self::splitRanges($header);

        foreach ($ranges as $range) {
            $parts = explode(';', $range);
            $type = strtolower(trim(array_shift($parts), " \t"));
            $q = 1.0;
            $hasMediaParameters = false;

            foreach ($parts as $parameter) {
                [$name, $value] = array_pad(explode('=', $parameter, 2), 2, '');
                if ('q' === strtolower(trim($name, " \t"))) {
                    $value = trim($value, " \t");
                    if (!preg_match('/^(?:0(?:\.[0-9]{0,3})?|1(?:\.0{0,3})?)$/D', $value)) {
                        continue 2;
                    }
                    $q = (float) $value;
                    break;
                } elseif ('' !== trim($name, " \t")) {
                    $hasMediaParameters = true;
                }
            }

            if (!$hasMediaParameters) {
                $preferences[] = [
                    'type' => $type,
                    'q' => $q,
                    'specificity' => self::calculateSpecificity($type),
                ];
            }
        }

        return $preferences;
    }

    /**
     * Splits only list commas outside quoted strings, honoring quoted-pair escapes.
     *
     * @return list<string>
     */
    private static function splitRanges(string $header): array
    {
        $ranges = [];
        $start = 0;
        $quoted = false;
        $escaped = false;
        $length = strlen($header);

        for ($index = 0; $index < $length; ++$index) {
            $character = $header[$index];
            if ($escaped) {
                $escaped = false;
                continue;
            }
            if ($quoted && '\\' === $character) {
                $escaped = true;
            } elseif ('"' === $character) {
                $quoted = !$quoted;
            } elseif (',' === $character && !$quoted) {
                $ranges[] = substr($header, $start, $index - $start);
                $start = $index + 1;
            }
        }

        $ranges[] = substr($header, $start);
        return $ranges;
    }

    /**
     * Calculates the specificity of a MIME type.
     *
     * Specificity MUST be determined using the following criteria:
     * - "* /*"    → specificity 0 (least specific)
     * - "type/*" → specificity 1 (partially specific)
     * - "type/subtype" → specificity 2 (fully specific)
     * This value SHALL be used to sort MIME types when q-values are equal.
     *
     * @param string $type the MIME type to evaluate
     *
     * @return int the calculated specificity score
     */
    private static function calculateSpecificity(string $type): int
    {
        if ('*/*' === $type) {
            return 0;
        }

        if (str_ends_with($type, '/*')) {
            return 1;
        }

        return 2;
    }

    /**
     * Determines whether a client-preferred type matches a server-supported type.
     *
     * This method MUST apply wildcard matching rules as defined in HTTP
     * content negotiation:
     * - "* /*" MUST match any type.
     * - A full exact match MUST be treated as the highest priority.
     * - A "type/*" wildcard MUST match any subtype within the given type.
     *
     * @param string $preference the MIME type from the client preference list
     * @param string $supported the server-supported MIME type
     *
     * @return bool true if the supported type matches the preference
     */
    private static function matches(string $preference, string $supported): bool
    {
        if ('*/*' === $preference || $preference === $supported) {
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
