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

        $actualSimple   = $this->differ->genDiff($firstFixture, $secondFixture, 'stylish');
        $actualNested   = $this->differ->genDiff($thirdFixture, $fourthFixture, 'stylish');
        $expectedSimple = file_get_contents($this->getPathToFixture('expectedSimple'));
        $expectedNested = file_get_contents($this->getPathToFixture('expectedNested'));

        $this->assertEquals($expectedSimple, $actualSimple);
        $this->assertEquals($expectedNested, $actualNested);
    }

    private function getPathToFixture(string $fixture): string
    {
        $path = __DIR__ . "/fixtures/" . $fixture;
        return $path;
    }
}
