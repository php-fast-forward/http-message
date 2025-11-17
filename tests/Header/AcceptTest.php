<?php

declare(strict_types=1);

namespace FastForward\Http\Message\Tests\Header;

use FastForward\Http\Message\Header\Accept;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Accept::class)]
class AcceptTest extends TestCase
{
    #[DataProvider('providerBestMatch')]
    public function testGetBestMatch(string $header, array $supported, ?Accept $expected): void
    {
        $this->assertSame($expected, Accept::getBestMatch($header, $supported));
    }

    public function testBestMatchWithEqualQualityFactors(): void
    {
        $header = 'text/html, application/xml;q=0.9, application/json;q=0.9';
        $supported = [Accept::ApplicationJson, Accept::ApplicationXml];
        $result = Accept::getBestMatch($header, $supported);

        $this->assertNotNull($result);
        $this->assertContains($result, $supported);
    }

    public static function providerBestMatch(): array
    {
        $allSupported = [Accept::ApplicationJson, Accept::ApplicationXml, Accept::TextHtml];

        return [
            'simple match: json' => [
                'application/json',
                $allSupported,
                Accept::ApplicationJson
            ],
            'simple match: html' => [
                'text/html',
                $allSupported,
                Accept::TextHtml
            ],
            'quality factor: json preferred' => [
                'text/html;q=0.8, application/json;q=0.9',
                $allSupported,
                Accept::ApplicationJson
            ],
            'quality factor: html preferred' => [
                'text/html;q=0.9, application/json;q=0.8',
                $allSupported,
                Accept::TextHtml
            ],
            'specificity: specific type preferred over wildcard' => [
                'application/json, */*;q=0.8',
                $allSupported,
                Accept::ApplicationJson
            ],
            'wildcard match: any' => [
                '*/*',
                [Accept::TextPlain, Accept::ApplicationJson],
                Accept::TextPlain
            ],
            'wildcard match: subtype' => [
                'text/*',
                [Accept::ApplicationJson, Accept::TextPlain],
                Accept::TextPlain
            ],
            'no match' => [
                'image/png',
                $allSupported,
                null
            ],
            'complex header' => [
                'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                [Accept::ApplicationJson, Accept::ApplicationXml],
                Accept::ApplicationXml
            ],
            'unsupported type with high q' => [
                'image/webp;q=1.0, application/json;q=0.8',
                [Accept::ApplicationJson, Accept::TextHtml],
                Accept::ApplicationJson
            ],
        ];
    }
}
