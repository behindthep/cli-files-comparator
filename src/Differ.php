<?php

namespace Diff\Comparator;

class Differ
{
    /**
     * Метод генерирует разницу между двумя файлами (структурами данных) по определённому формату
     */
    public static function genDiff(string $pathToFirstFile, string $pathToSecondFile, string $format = 'stylish'): string
    {
        $parsedFirstFile  = Parsers::parse($pathToFirstFile);
        $parsedSecondFile = Parsers::parse($pathToSecondFile);

        $diff = self::makeDiff($parsedFirstFile, $parsedSecondFile);

        $result = Formatters::makeFormat($diff, $format);

        return $result;
    }

    private function makeDiff(array $parsedFirstFile, array $parsedSecondFile): array
    {
        $uniqueKeys = $this->getSortedUniqueKeys($parsedFirstFile, $parsedSecondFile);

        $differ = [];

        foreach ($uniqueKeys as $key) {
            $firstValue  = $parsedFirstFile[$key]  ?? null;
            $secondValue = $parsedSecondFile[$key] ?? null;

            if (is_array($firstValue) && is_array($secondValue)) {
                $differ[] = [
                    'status' => 'nested',
                    'key'    => $key,
                    'value1' => $this->makeDiff($firstValue, $secondValue),
                    'value2' => null
                ];
                continue;
            }
            if (!array_key_exists($key, $parsedFirstFile)) {
                $differ[] = [
                    'status' => 'added',
                    'key'    => $key,
                    'value1' => $secondValue,
                    'value2' => null
                ];
                continue;
            }
            if (!array_key_exists($key, $parsedSecondFile)) {
                $differ[] = [
                    'status' => 'removed',
                    'key'    => $key,
                    'value1' => $firstValue,
                    'value2' => null
                ];
                continue;
            }
            if ($firstValue === $secondValue) {
                $differ[] = [
                    'status' => 'unchanged',
                    'key'    => $key,
                    'value1' => $firstValue,
                    'value2' => $firstValue
                ];
                continue;
            }
            if ($firstValue !== $secondValue) {
                $differ[] = [
                    'status' => 'updated',
                    'key'    => $key,
                    'value1' => $firstValue,
                    'value2' => $secondValue
                ];
                continue;
            }
        }

        return $differ;
    }

    private function getSortedUniqueKeys(array $parsedFirstFile, array $parsedSecondFile): array
    {
        $firstFileKeys  = array_keys($parsedFirstFile);
        $secondFileKeys = array_keys($parsedSecondFile);

        $filesKeys  = array_merge($firstFileKeys, $secondFileKeys);
        $uniqueKeys = array_unique($filesKeys);

        sort($uniqueKeys);

        return $uniqueKeys;
    }
}
