<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use function Gendiff\Differ\genDiff;

class GenDiffTest extends TestCase
{
    private string $fixturesPath = __DIR__ . '/fixtures';

    private function getFilePath(string $name): string
    {
        return "{$this->fixturesPath}/{$name}";
    }

    private function getExpectedFileData(string $format): string
    {
        $fileName = "expected-{$format}.txt";
        $filePath = $this->getFilePath($fileName);
        $data = file_get_contents($filePath);

        return rtrim($data);
    }

    public static function dataProvider(): array
    {
        return [
            'json - json' => ['file1.json', 'file2.json'],
            'json - yml' => ['file1.json', 'file2.yml'],
            'yml - json' => ['file1.yml', 'file2.json'],
            'yml - yml' => ['file1.yml', 'file2.yml']
        ];
    }

    #[DataProvider('dataProvider')]
    public function testDefault(string $firstFile, string $secondFile): void
    {
        $firstFilePath = $this->getFilePath($firstFile);
        $secondFilePath = $this->getFilePath($secondFile);

        $format = "stylish";
        $expected = $this->getExpectedFileData($format);
        $this->assertEquals($expected, genDiff($firstFilePath, $secondFilePath));
    }

    #[DataProvider('dataProvider')]
    public function testStylish(string $firstFile, string $secondFile): void
    {
        $firstFilePath = $this->getFilePath($firstFile);
        $secondFilePath = $this->getFilePath($secondFile);

        $format = "stylish";
        $expected = $this->getExpectedFileData($format);
        $this->assertEquals($expected, genDiff($firstFilePath, $secondFilePath, $format));
    }

    #[DataProvider('dataProvider')]
    public function testJson(string $firstFile, string $secondFile): void
    {
        $firstFilePath = $this->getFilePath($firstFile);
        $secondFilePath = $this->getFilePath($secondFile);

        $format = "json";
        $expected = $this->getExpectedFileData($format);
        $this->assertEquals($expected, genDiff($firstFilePath, $secondFilePath, $format));
    }

    #[DataProvider('dataProvider')]
    public function testPlain(string $firstFile, string $secondFile): void
    {
        $firstFilePath = $this->getFilePath($firstFile);
        $secondFilePath = $this->getFilePath($secondFile);

        $format = "plain";
        $expected = $this->getExpectedFileData($format);
        $this->assertEquals($expected, genDiff($firstFilePath, $secondFilePath, $format));
    }
}
