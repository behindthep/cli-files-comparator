<?php

namespace Diff\Comparator;

class Differ
{
    public static function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
    {
        $parsedFile1  = Parsers::parse($pathToFile1);
        $parsedFile2  = Parsers::parse($pathToFile2);

        $difference    = self::makeDiff($parsedFile1, $parsedFile2);
        $formattedDiff = Formatters::makeFormat($difference, $format);

        return $formattedDiff;
    }

    private static function makeDiff(array $parsedFile1, array $parsedFile2): array
    {
        $uniqueKeys = self::getSortedUniqueKeys($parsedFile1, $parsedFile2);

        return array_map(fn($key) => self::checkDifference($key, $parsedFile1, $parsedFile2), $uniqueKeys);
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
