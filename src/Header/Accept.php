<?php

declare(strict_types=1);

/**
 * This file is part of php-fast-forward/http-message.
 *
 * This source file is subject to the license bundled
 * with this source code in the file LICENSE.
 *
 * @link      https://github.com/php-fast-forward/http-message
 * @copyright Copyright (c) 2025 Felipe Sayão Lobato Abreu <github@mentordosnerds.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

namespace FastForward\Http\Message\Header;

/**
 * Enum Accept.
 *
 * Represents common HTTP Accept header values and provides robust helpers for
 * content negotiation in accordance with RFC 2119 requirement levels.
 *
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
     * - Quality factors (q-values), which MUST be sorted in descending order.
     * - Specificity preference, where more explicit types MUST be preferred over
     *   wildcard types when q-values are equal.
     *
     * If a match cannot be found in the list of supported server types,
     * the method MUST return null.
     *
     * @param string $acceptHeader   the raw HTTP Accept header value
     * @param self[] $supportedTypes an array of enum cases the server supports
     *
     * @return null|self the best match, or null if no acceptable type exists
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
     * Parses the Accept header string into a sorted list of client preferences.
     *
     * This method MUST:
     * - Extract MIME types from the header string.
     * - Parse quality factors (q-values), defaulting to 1.0 when omitted.
     * - Calculate specificity, which SHALL be used as a secondary sort criterion.
     * - Sort results by descending q-value and descending specificity.
     *
     * The resulting array MUST represent the client’s explicit and wildcard
     * preferences in the correct HTTP negotiation order.
     *
     * @param string $header the raw Accept header string
     *
     * @return array<int, array{type: string, q: float, specificity: int}>
     */
    private static function parseHeader(string $header): array
    {
        $preferences = [];

        // Captures a type and optional q-value while ignoring other parameters.
        $pattern = '/(?<type>[^,;]+)(?:;[^,]*q=(?<q>[0-9.]+))?/';

        if (\preg_match_all($pattern, $header, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $type = \mb_trim($match['type']);
                $q    = isset($match['q']) && '' !== $match['q'] ? (float) $match['q'] : 1.0;

                $preferences[] = [
                    'type'        => $type,
                    'q'           => $q,
                    'specificity' => self::calculateSpecificity($type),
                ];
            }
        }

        \usort(
            $preferences,
            static function (array $a, array $b): int {
                if ($a['q'] !== $b['q']) {
                    return $b['q'] <=> $a['q'];
                }

                return $b['specificity'] <=> $a['specificity'];
            }
        );

        return $preferences;
    }

    /**
     * Calculates the specificity of a MIME type.
     *
     * Specificity MUST be determined using the following criteria:
     * - "* /*"    → specificity 0 (least specific)
     * - "type/*" → specificity 1 (partially specific)
     * - "type/subtype" → specificity 2 (fully specific)
     *
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

        if (\str_ends_with($type, '/*')) {
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
     * @param string $supported  the server-supported MIME type
     *
     * @return bool true if the supported type matches the preference
     */
    private static function matches(string $preference, string $supported): bool
    {
        if ('*/*' === $preference || $preference === $supported) {
            return true;
        }

        if (\str_ends_with($preference, '/*')) {
            $prefix = \strtok($preference, '/');
            if (\str_starts_with($supported, $prefix . '/')) {
                return true;
            }
        }

        return false;
    }
}
