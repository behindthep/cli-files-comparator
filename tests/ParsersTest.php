<?php

namespace Diff\Comparator\Tests;

use PHPUnit\Framework\TestCase;
use Diff\Comparator\Parsers;

class ParsersTest extends TestCase
{
    public function testParserUnsupportedFormat(): void
    {
        $this->expectException(\Exception::class);

        $unsupportedFile = $this->getPathToFixture("file1.txt");
        $parsedFile      = Parsers::parse($unsupportedFile);
        print_r($parsedFile);
    }

    private function getPathToFixture(string $fixture): string
    {
        return __DIR__ . "/fixtures/" . $fixture;
    }
}
