<?php

namespace Diff\Comparator\Formatters;

class Stylish
{
    public static function stylishFormat(array $difference): string
    {
        $formattedDiff   = self::makeStringFromDiff($difference);
        $stringifiedDiff = implode("\n", $formattedDiff);

        return "{\n$stringifiedDiff\n}\n";
    }

    private static function getSpaces(int $numberOfRepetitions): string
    {
        return str_repeat('    ', $numberOfRepetitions);
    }

    private static function stringifyValue(mixed $value, int $level): mixed
    {
        switch (true) {
            case is_null($value):
                return 'null';
            case is_bool($value):
                return $value ? 'true' : 'false';
            case is_array($value):
                $stringifiedArr = self::convertArrayToString($value, $level);
                $spaces         = self::getSpaces($level);
                return "{$stringifiedArr}{$spaces}";
            default:
                return "$value";
        }
    }

    private static function convertArrayToString(array $arr, int $level): string
    {
        $nextLevel = $level + 1;

        $formattedArr = array_map(function ($key) use ($arr, $nextLevel) {
            $value  = self::stringifyValue($arr[$key], $nextLevel);
            $spaces = self::getSpaces($nextLevel);
            return "\n{$spaces}{$key}: $value";
        }, array_keys($arr));

        return implode('', $formattedArr);
    }

    // по названию должна возвращять строку а не массив. можно объединить эту и stylishFormat
    private static function makeStringFromDiff(array $difference, int $level = 0): array
    {
        $stringifiedDiff = [];
        $nextLevel       = $level + 1;
        $spaces          = self::getSpaces($level);

        foreach ($difference as $node) {
            $status = $node['status'];
            $key    = $node['key'];
            $value1 = $node['value1'];
            $value2 = $node['value2'];

            switch ($status) {
                case 'nested':
                    $nested            = self::makeStringFromDiff($value1, $nextLevel);
                    $stringifiedNest   = implode("\n", $nested);
                    $stringifiedDiff[] = "$spaces    $key: {\n$stringifiedNest\n$spaces    }";
                    break;
                case 'unchanged':
                    $stringifiedValue1 = self::stringifyValue($value1, $nextLevel);
                    $stringifiedDiff[] = "$spaces    $key: $stringifiedValue1";
                    break;
                case 'added':
                    $stringifiedValue1 = self::stringifyValue($value1, $nextLevel);
                    $stringifiedDiff[] = "$spaces  + $key: $stringifiedValue1";
                    break;
                case 'removed':
                    $stringifiedValue1 = self::stringifyValue($value1, $nextLevel);
                    $stringifiedDiff[] = "$spaces  - $key: $stringifiedValue1";
                    break;
                case 'updated':
                    $stringifiedValue1 = self::stringifyValue($value1, $nextLevel);
                    $stringifiedValue2 = self::stringifyValue($value2, $nextLevel);
                    $stringifiedDiff[] = "$spaces  - $key: $stringifiedValue1\n$spaces  + $key: $stringifiedValue2";
                    break;
            }
        }

        return $stringifiedDiff;
    }
}
