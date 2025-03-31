<?php

namespace Diff\Comparator\Tests;

use PHPUnit\Framework\TestCase;
use Diff\Comparator\Differ;

class GenDiffTest extends TestCase
{
    private function getPathToFixture(string $fixture): string
    {
        $path = __DIR__ . "/fixtures/" . $fixture;
        return $path;
    }

    public static function extensionProvider(): array
    {
        return [
            ['json'],
            ['yml'],
            ['yaml']
        ];
    }

    /**
     * @dataProvider extensionProvider
     */
    public function testGenDiff(string $extension): void
    {
        $firstFixture  = $this->getPathToFixture("file1.$extension");
        $secondFixture = $this->getPathToFixture("file2.$extension");
        $thirdFixture  = $this->getPathToFixture("file3.$extension");
        $fourthFixture = $this->getPathToFixture("file4.$extension");

        $actualSimpleStylish   = Differ::genDiff($firstFixture, $secondFixture, 'stylish');
        $expectedSimpleStylish = file_get_contents($this->getPathToFixture('expectedSimpleStylish'));
        $this->assertEquals($expectedSimpleStylish, $actualSimpleStylish);

        $actualNestedStylish   = Differ::genDiff($thirdFixture, $fourthFixture, 'stylish');
        $expectedNestedStylish = file_get_contents($this->getPathToFixture('expectedNestedStylish'));
        $this->assertEquals($expectedNestedStylish, $actualNestedStylish);

        $actualPlain           = Differ::genDiff($thirdFixture, $fourthFixture, 'plain');
        $expectedPlain         = file_get_contents($this->getPathToFixture('expectedPlain'));
        $this->assertEquals($expectedPlain, $actualPlain);
    }
}
