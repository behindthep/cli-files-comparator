<?php

namespace Diff\Comparator;

class Differ
{
    /**
     * Метод генерирует разницу между двумя файлами (структурами данных) по определённому формату
     */
    public function genDiff(string $pathToFirstFile, string $pathToSecondFile, string $format = 'stylish'): string
    {
        $parser = new Parsers();
        $parsedFirstFile  = $parser->parse($pathToFirstFile);
        $parsedSecondFile = $parser->parse($pathToSecondFile);

        $diff = $this->makeDiff($parsedFirstFile, $parsedSecondFile);

        $formatter = new Formatters();
        $result = $formatter->makeFormat($diff, $format);
    
        return $result;
    }

    private function makeDiff(array $parsedFirstFile, array $parsedSecondFile): string
    {
        $uniqueKeys = array_unique(array_merge(array_keys($parsedFirstFile), array_keys($parsedSecondFile)));

        sort($uniqueKeys);

        $difference = '';

        foreach ($uniqueKeys as $key) {
            $firstValue  = $parsedFirstFile[$key]  ?? null;
            $secondValue = $parsedSecondFile[$key] ?? null;

            if (array_key_exists($key, $parsedFirstFile) && array_key_exists($key, $parsedSecondFile)) {
                $firstData  = $this->stringifyValue($firstValue);
                $secondData = $this->stringifyValue($secondValue);

                if ($firstData === $secondData) {
                    $difference .= "    $key: $firstData\n";
                } else {
                    $difference .= "  - $key: $firstData\n  + $key: $secondData\n";
                }
            } elseif (array_key_exists($key, $parsedFirstFile) && !array_key_exists($key, $parsedSecondFile)) {
                $firstData   = $this->stringifyValue($firstValue);
                $difference .= "  - $key: $firstData\n";
            } elseif (!array_key_exists($key, $parsedFirstFile) && array_key_exists($key, $parsedSecondFile)) {
                $secondData  = $this->stringifyValue($secondValue);
                $difference .= "  + $key: $secondData\n";
            }
        }

        return $difference;
    }

    private function stringifyValue(mixed $value): mixed
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        } 
        elseif (is_null($value)) {
            return 'null';
        }

        return $value;
    }
}
