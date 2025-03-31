<?php

namespace Diff\Comparator\Formatters;

class Stylish
{
    public static function stylishFormat(array $diff): string
    {
        $formattedDiff = self::makeStringFromDiff($diff);
        $result = implode("\n", $formattedDiff);
        return "{\n$result\n}\n";
    }

    private static function makeStringFromDiff(array $diff, int $level = 0): array
    {
        $stringifiedDiff = [];
        $spaces = self::getSpaces($level);
        $nextLevel = $level + 1;

        foreach ($diff as $node) {
            $status = $node['status'];
            $key    = $node['key'];
            $value1 = $node['value1'];
            $value2 = $node['value2'];

            switch ($status) {
                case 'nested':
                    $nested = self::makeStringFromDiff($value1, $nextLevel);
                    $stringifiedNest = implode("\n", $nested);
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
            }
        }

        return $stringifiedDiff;
    }

    private static function getSpaces(int $level): string
    {
        return str_repeat('    ', $level);
    }

    private static function stringifyValue(mixed $value, int $level): mixed
    {
        if (is_null($value)) {
            return 'null';
        } elseif (is_bool($value)) {
            return $value ? 'true' : 'false';
        } elseif (is_array($value)) {
            $result = self::convertArrayToString($value, $level);
            $spaces = self::getSpaces($level);
            return "$result\n$spaces";
        }

        return "$value";
    }

    private static function convertArrayToString(array $value, int $level): string
    {
        $keys = array_keys($value);
        $result = [];
        $nextLevel = $level + 1;

        $callback = function ($key) use ($value, $nextLevel) {
            $newValue = self::stringifyValue($value[$key], $nextLevel);
            $spaces = self::getSpaces($nextLevel);
            return "\n{$spaces}{$key}: $newValue";
        };

        $result = array_map($callback, $keys);

        return implode('', $result);
    }
}
