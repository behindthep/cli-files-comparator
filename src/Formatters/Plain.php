<?php

namespace Diff\Comparator\Formatters;

use function Functional\flatten;

class Plain
{
    public static function plainFormat(array $difference): string
    {
        $formattedDiff   = self::makeStringsFromDiff($difference);
        $stringifiedDiff = implode("\n", $formattedDiff);
        return "{$stringifiedDiff}\n";
    }

    private static function makeStringsFromDiff(array $difference, string $path = ''): array
    {
        $arrayOfDifferences = flatten(array_map(function ($node) use ($path) {
            [
                'status' => $status,
                'key'    => $key,
                'value1' => $value1,
                'value2' => $value2
            ] = $node;

            $fullPath = "{$path}{$key}";

            switch ($status) {
                case 'nested':
                    return self::makeStringsFromDiff($value1, "{$path}{$key}.");
                case 'added':
                    $stringifiedValue1 = self::stringifyValue($value1);
                    return "Property '$fullPath' was added with value: $stringifiedValue1";
                case 'removed':
                    return "Property '$fullPath' was removed";
                case 'updated':
                    $stringifiedValue1 = self::stringifyValue($value1);
                    $stringifiedValue2 = self::stringifyValue($value2);
                    return "Property '$fullPath' was updated. From $stringifiedValue1 to $stringifiedValue2";
                case 'unchanged':
                    return;
            }
        }, $difference));
        return array_filter($arrayOfDifferences, fn($value) => !is_null($value));
    }

    private static function stringifyValue(mixed $value): mixed
    {
        return match (true) {
            is_null($value)    => 'null',
            is_bool($value)    => $value ? 'true' : 'false',
            is_array($value)   => '[complex value]',
            is_numeric($value) => $value,
            default            => "'$value'",
        };
    }
}
