<?php

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public static function urlProvider(): array
    {
        return [
            ['https://user:pass@example.com:8080/path?x=1#frag'],
            ['http://example.com'],
            ['ftp://host/path/to/file'],
        ];
    }

    #[DataProvider('urlProvider')]
    public function testMatchesNativeParseUrl(string $url): void
    {
        $expected = parse_url($url);
        $actual = parse_url_custom($url);
        $this->assertEquals($expected, $actual);
    }
}