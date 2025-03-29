<?php

namespace Diff\Comparator;

class Differ
{
    /**
     * Метод генерирует разницу между двумя файлами (структурами данных) по определённому формату
     */
    public function genDiff(string $pathToFirstFile, string $pathToSecondFile, string $format = 'stylish'): string
    {
        $firstFile  = file_get_contents('tests/fixtures/' . $pathToFirstFile);
        $secondFile = file_get_contents('tests/fixtures/' . $pathToSecondFile);

        $parsedFirstFile  = json_decode($firstFile, true);
        $parsedSecondFile = json_decode($secondFile, true);

        $uniqueKeys = array_unique(array_merge(array_keys($parsedFirstFile), array_keys($parsedSecondFile)));

        sort($uniqueKeys);

        $difference = '';

        foreach ($uniqueKeys as $key) {
            if (array_key_exists($key, $parsedFirstFile) && array_key_exists($key, $parsedSecondFile)) {
                $firstData  = $this->convertToStringIfItsBool($parsedFirstFile[$key]);
                $secondData = $this->convertToStringIfItsBool($parsedSecondFile[$key]);

                if ($firstData === $secondData) {
                    $difference .= "    $key: $firstData\n";
                } else {
                    $difference .= "  - $key: $firstData\n  + $key: $secondData\n";
                }
            } elseif (array_key_exists($key, $parsedFirstFile) && !array_key_exists($key, $parsedSecondFile)) {
                $firstData   = $this->convertToStringIfItsBool($parsedFirstFile[$key]);
                $difference .= "  - $key: $firstData\n";
            } elseif (!array_key_exists($key, $parsedFirstFile) && array_key_exists($key, $parsedSecondFile)) {
                $secondData  = $this->convertToStringIfItsBool($parsedSecondFile[$key]);
                $difference .= "  + $key: $secondData\n";
            }
        }

        $difference = "{\n$difference}\n";
        return $difference;
    }

    private function convertToStringIfItsBool(mixed $value): mixed
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return $value;
    }
}
