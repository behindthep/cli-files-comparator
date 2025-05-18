<?php

namespace Gendiff\Formatters;

use Gendiff\Differ;

class Plain
{
    private const COMPARE_TEXT_MAP = [
        Differ::ADDED => 'added',
        Differ::DELETED => 'removed',
        Differ::CHANGED => 'updated',
        Differ::UNCHANGED => '',
        Differ::NESTED => '[complex value]',
    ];

    public static function render(array $data): string
    {
        $result = self::iter($data['children']);
        return rtrim(implode($result), " \n");
    }

    private static function iter(array $value, array $acc = []): array
    {
        $func = function ($val) use ($acc) {

            if (!is_array($val)) {
                return self::toString($val);
            }

            if (!array_key_exists(0, $val) && !array_key_exists('type', $val)) {
                return self::toString($val);
            }

            $key = $val['key'];
            $compare = $val['type'];
            $compareText = self::COMPARE_TEXT_MAP[$compare];
            $accNew = [...$acc, ...[$key]];

            return match ($compare) {
                Differ::ADDED => sprintf(
                    "Property '%s' was %s with value: %s\n",
                    implode('.', $accNew),
                    $compareText,
                    self::toString($val['value']),
                ),
                Differ::DELETED => sprintf(
                    "Property '%s' was %s\n",
                    implode('.', $accNew),
                    $compareText,
                ),
                Differ::CHANGED => sprintf(
                    "Property '%s' was %s. From %s to %s\n",
                    implode('.', $accNew),
                    $compareText,
                    self::toString($val['value1']),
                    self::toString($val['value2']),
                ),
                Differ::NESTED => implode(self::iter($val['children'], $accNew)),
                default => null,
            };
        };

        $result = array_map($func, $value);
        return $result;
    }

    private static function toString(mixed $value): string
    {
        return match (true) {
            $value === true => 'true',
            $value === false => 'false',
            is_null($value) => 'null',
            is_array($value) || is_object($value) => '[complex value]',
            is_string($value) => "'{$value}'",
            default => trim((string) $value, "'")
        };
    }
}
