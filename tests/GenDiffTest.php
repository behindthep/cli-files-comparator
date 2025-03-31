<?php

namespace Diff\Comparator\Tests;

use PHPUnit\Framework\TestCase;
use Diff\Comparator\Differ;

class GenDiffTest extends TestCase
{
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

        $actualSimple   = Differ::genDiff($firstFixture, $secondFixture, 'stylish');
        $expectedSimple = file_get_contents($this->getPathToFixture('expectedSimple'));
        $this->assertEquals($expectedSimple, $actualSimple);

        $actualStylish   = Differ::genDiff($thirdFixture, $fourthFixture, 'stylish');
        $expectedStylish = file_get_contents($this->getPathToFixture('expectedStylish'));
        $this->assertEquals($expectedStylish, $actualStylish);

        $actualPlain   = Differ::genDiff($thirdFixture, $fourthFixture, 'plain');
        $expectedPlain = file_get_contents($this->getPathToFixture('expectedPlain'));
        $this->assertEquals($expectedPlain, $actualPlain);
    }

    private function getPathToFixture(string $fixture): string
    {
        $path = __DIR__ . "/fixtures/" . $fixture;
        return $path;
    }
}
