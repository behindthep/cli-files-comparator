<?php

namespace Diff\Comparator;

class Differ
{
    /**
     * Метод генерирует разницу между двумя файлами (структурами данных) по определённому формату
     */
    public static function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
    {
        $parsedFirstFile  = Parsers::parse($pathToFile1);
        $parsedSecondFile = Parsers::parse($pathToFile2);

        $difference    = self::makeDiff($parsedFirstFile, $parsedSecondFile);
        $formattedDiff = Formatters::makeFormat($difference, $format);

        return $formattedDiff;
    }

    private static function getSortedUniqueKeys(array $associativeArr1, array $associativeArr2): array
    {
        $keys1  = array_keys($associativeArr1);
        $keys2  = array_keys($associativeArr2);

        $combinedKeys  = array_merge($keys1, $keys2);
        $uniqueKeys    = array_unique($combinedKeys);
        sort($uniqueKeys);

        return $uniqueKeys;
    }

    private static function makeDiff(array $parsedFirstFile, array $parsedSecondFile): array
    {
        $uniqueKeys = self::getSortedUniqueKeys($parsedFirstFile, $parsedSecondFile);

        $difference = [];

        foreach ($uniqueKeys as $key) {
            $firstValue  = $parsedFirstFile[$key]  ?? null;
            $secondValue = $parsedSecondFile[$key] ?? null;

            if (is_array($firstValue) && is_array($secondValue)) {
                $difference[] = [
                    'status' => 'nested',
                    'key'    => $key,
                    'value1' => self::makeDiff($firstValue, $secondValue),
                    'value2' => null
                ];
            } elseif (!array_key_exists($key, $parsedFirstFile)) {
                $difference[] = [
                    'status' => 'added',
                    'key'    => $key,
                    'value1' => $secondValue,
                    'value2' => null
                ];
            } elseif (!array_key_exists($key, $parsedSecondFile)) {
                $difference[] = [
                    'status' => 'removed',
                    'key'    => $key,
                    'value1' => $firstValue,
                    'value2' => null
                ];
            } elseif ($firstValue === $secondValue) {
                $difference[] = [
                    'status' => 'unchanged',
                    'key'    => $key,
                    'value1' => $firstValue,
                    'value2' => $secondValue
                ];
            } elseif ($firstValue !== $secondValue) {
                $difference[] = [
                    'status' => 'updated',
                    'key'    => $key,
                    'value1' => $firstValue,
                    'value2' => $secondValue
                ];
            }
        }

        return $difference;
    }
}
