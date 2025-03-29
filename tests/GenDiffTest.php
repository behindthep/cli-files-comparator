<?php
 
namespace Diff\Comparator\Tests;

use PHPUnit\Framework\TestCase;

use Diff\Comparator\Differ;

class GenDiffTest extends TestCase
{
    private $differ;

    protected function setUp(): void 
    {
        $this->differ = new Differ();
    }

    public static function extensionProvider(): array
    {
        return [
            ['json'],
            // ['yaml']
        ];
    }

    /**
     * @dataProvider extensionProvider
     */
    public function testGenDiff(string $extension): void
    {
        $firstFixture  = $this->getPathToFixture("file1.$extension");
        $secondFixture = $this->getPathToFixture("file2.$extension");

        $actual   = $this->differ->genDiff($firstFixture, $secondFixture, 'plain');
        $expected = file_get_contents($this->getPathToFixture('expectedPlainJson'));

        $this->assertEquals($expected, $actual);
    }

    private function getPathToFixture(string $fixture): string
    {
        $path = __DIR__ . "/fixtures/" . $fixture;
        return $path;
    }
}
