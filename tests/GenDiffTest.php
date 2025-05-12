<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Gendiff\Differ;

class GenDiffTest extends TestCase
{
    public static function extensionProvider(): array
    {
        return [
            ['json'],
            ['yml']
        ];
    }

    /**
     * @dataProvider extensionProvider
     */
    public function testGenDiff(string $extension): void
    {
        $fixture1        = $this->getPathToFixture("file1.$extension");
        $fixture2        = $this->getPathToFixture("file2.$extension");

        $actualStylish   = Differ::genDiff($fixture1, $fixture2, 'stylish');
        $expectedStylish = file_get_contents($this->getPathToFixture('expectedStylish'));
        $this->assertEquals($expectedStylish, $actualStylish);

        $actualPlain     = Differ::genDiff($fixture1, $fixture2, 'plain');
        $expectedPlain   = file_get_contents($this->getPathToFixture('expectedPlain'));
        $this->assertEquals($expectedPlain, $actualPlain);

        $actualJson      = Differ::genDiff($fixture1, $fixture2, 'json');
        $expectedJson    = file_get_contents($this->getPathToFixture('expectedJson'));
        $this->assertEquals($expectedJson, $actualJson);
    }

    private function getPathToFixture(string $fixture): string
    {
        return __DIR__ . "/fixtures/" . $fixture;
    }
}
