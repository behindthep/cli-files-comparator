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

    private function makeStringFromDiff(array $diff, int $level = 0): array
    {
        $stringifiedDiff = [];
        $spaces = $this->getSpaces($level);
        $nextLevel = $level + 1;

        foreach ($diff as $node) {
            $status = $node['status'];
            $key    = $node['key'];
            $value1 = $node['value1'];
            $value2 = $node['value2'];

            switch ($status) {
                case 'nested':
                    $nested = $this->makeStringFromDiff($value1, $nextLevel);
                    $stringifiedNest = implode("\n", $nested);
                    $stringifiedDiff[] = "$spaces    $key: {\n$stringifiedNest\n$spaces    }";
                    break;
                case 'unchanged':
                    $stringifiedValue1 = $this->stringifyValue($value1, $nextLevel);
                    $stringifiedDiff[] = "$spaces    $key: $stringifiedValue1";
                    break;
                case 'added':
                    $stringifiedValue1 = $this->stringifyValue($value1, $nextLevel);
                    $stringifiedDiff[] = "$spaces  + $key: $stringifiedValue1";
                    break;
                case 'removed':
                    $stringifiedValue1 = $this->stringifyValue($value1, $nextLevel);
                    $stringifiedDiff[] = "$spaces  - $key: $stringifiedValue1";
                    break;
                case 'updated':
                    $stringifiedValue1 = $this->stringifyValue($value1, $nextLevel);
                    $stringifiedValue2 = $this->stringifyValue($value2, $nextLevel);
                    $stringifiedDiff[] = "$spaces  - $key: $stringifiedValue1\n$spaces  + $key: $stringifiedValue2";
            }
        }

        return $stringifiedDiff;
    }

    private function getSpaces(int $level): string
    {
        return str_repeat('    ', $level);
    }

    private function stringifyValue(mixed $value, int $level): mixed
    {
        if (is_null($value)) {
            return 'null';
        } elseif (is_bool($value)) {
            return $value ? 'true' : 'false';
        } elseif (is_array($value)) {
            $result = $this->convertArrayToString($value, $level);
            $spaces = $this->getSpaces($level);
            return "$result\n$spaces";
        }

        return "$value";
    }

    private function convertArrayToString(array $value, int $level): string
    {
        $keys = array_keys($value);
        $result = [];
        $nextLevel = $level + 1;

        $callback = function ($key) use ($value, $nextLevel) {
            $newValue = $this->stringifyValue($value[$key], $nextLevel);
            $spaces = $this->getSpaces($nextLevel);
            return "\n{$spaces}{$key}: $newValue";
        };

        $result = array_map($callback, $keys);

        return implode('', $result);
    }
}
