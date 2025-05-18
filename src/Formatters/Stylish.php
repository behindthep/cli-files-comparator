<?php

namespace Gendiff\Formatters;

use Gendiff\Differ;

class Stylish
{
    private const SPACECOUNT = 4;
    private const REPLACER = ' ';
    private const COMPARE_TEXT_SYMBOL_MAP = [
        Differ::ADDED => '+',
        Differ::DELETED => '-',
        Differ::CHANGED => ' ',
        Differ::NESTED => ' ',
        Differ::UNCHANGED => ' ',
    ];

    public static function render(array $data): string
    {
        $result = self::iter($data['children']);
        return $result;
    }

    private static function iter(array $value, int $depth = 1): string
    {
        $func = function ($val) use ($depth) {
            if (!is_array($val)) {
                return self::toString($val);
            }

            if (!array_key_exists(0, $val) && !array_key_exists('type', $val)) {
                return self::toString($val);
            }

            $compare = $val['type'];
            $delete = self::COMPARE_TEXT_SYMBOL_MAP[Differ::DELETED];
            $add = self::COMPARE_TEXT_SYMBOL_MAP[Differ::ADDED];
            $compareSymbol = self::COMPARE_TEXT_SYMBOL_MAP[$compare];
            $key = $val['key'];

            return match ($compare) {
                Differ::CHANGED => self::structure($val['value1'], $key, $delete, $depth)
                . self::structure($val['value2'], $key, $add, $depth),
                Differ::NESTED => self::structure(
                    self::iter($val['children'], $depth + 1),
                    $key, $compareSymbol, $depth
                ),
                default => self::structure($val['value'], $key, $compareSymbol, $depth),
            };
        };

        $result = array_map($func, $value);
        $closeBracketIndentSize = $depth * self::SPACECOUNT;
        $closeBracketIndent = $closeBracketIndentSize > 0
        ? str_repeat(self::REPLACER, $closeBracketIndentSize - self::SPACECOUNT) : '';

        return "{\n" . implode($result) . "{$closeBracketIndent}}";
    }

    private static function structure(mixed $value, string $key, string $compareSymbol, int $depth): string
    {
        $indentSize = ($depth * self::SPACECOUNT) - 2;
        $currentIndent = str_repeat(self::REPLACER, $indentSize);
        $depthNested = $depth + 1;
        $valueStructured = self::depthStructuring($value, $depthNested);

        $result = sprintf(
            "%s%s %s: %s\n",
            $currentIndent,
            $compareSymbol,
            $key,
            $valueStructured,
        );
        return $result;
    }

    private static function depthStructuring(mixed $value, int $depth): string
    {
        if (!is_array($value)) {
            return self::toString($value);
        }

        $indentSize = $depth * self::SPACECOUNT;
        $currentIndent = str_repeat(self::REPLACER, $indentSize);

        $fun = function ($key, $val) use ($depth, $currentIndent) {
            return sprintf(
                "%s%s: %s\n",
                $currentIndent,
                $key,
                self::depthStructuring($val, $depth + 1),
            );
        };

        $result = array_map($fun, array_keys($value), $value);
        $closeBracketIndent = str_repeat(self::REPLACER, $indentSize - self::SPACECOUNT);

        return "{\n" . implode($result) . "{$closeBracketIndent}}";
    }

    private static function toString(mixed $value): string
    {
        return match (true) {
            $value === true => 'true',
            $value === false => 'false',
            is_null($value) => 'null',
            default => trim((string) $value, "'")
        };
    }
}
