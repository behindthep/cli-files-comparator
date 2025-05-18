<?php

namespace Gendiff;

use Gendiff\{Parser, Formatter};

use function Functional\sort as fsort;

class Differ
{
    public const UNCHANGED = 'unchanged';
    public const CHANGED = 'changed';
    public const ADDED = 'added';
    public const DELETED = 'deleted';
    public const NESTED = 'nested';

    public static function genDiff(string $file1, string $file2, string $format = 'stylish'): string
    {
        ['extension' => $extension1, 'content' => $contentFile1] = self::getContents($file1);
        ['extension' => $extension2, 'content' => $contentFile2]  = self::getContents($file2);

        $valueFile1 = Parser::parser($extension1, $contentFile1);
        $valueFile2 = Parser::parser($extension2, $contentFile2);

        $valueDiff = self::buildDiff($valueFile1, $valueFile2);
        $valueDiffWithRoot = self::addingRootNode($valueDiff);

        return Formatter::format($valueDiffWithRoot, $format);
    }

    private static function getContents(string $path): array
    {
        if (!file_exists($path)) {
            throw new \Exception("Invalid file path: {$path}");
        }

        return [
            'extension' => pathinfo($path, PATHINFO_EXTENSION),
            'content' => file_get_contents($path),
        ];
    }

    private static function buildDiff(array $first, array $second): array
    {
        $uniqueKeys = array_unique(array_merge(array_keys($first), array_keys($second)));
        $sortedArray = fsort($uniqueKeys, function ($first, $second) {
            return $first <=> $second;
        });

        return array_map(function ($key) use ($first, $second) {
            $valueFirst = $first[$key] ?? null;
            $valueSecond = $second[$key] ?? null;

            if (
                (is_array($valueFirst) && !array_is_list($valueFirst)) &&
                (is_array($valueSecond) && !array_is_list($valueSecond))
            ) {
                return [
                    'key' => $key,
                    'type' => self::NESTED,
                    'children' => self::buildDiff($valueFirst, $valueSecond),
                ];
            }

            if (!array_key_exists($key, $first)) {
                return [
                    'key' => $key,
                    'type' => self::ADDED,
                    'value' => $valueSecond,
                ];
            }

            if (!array_key_exists($key, $second)) {
                return [
                    'key' => $key,
                    'type' => self::DELETED,
                    'value' => $valueFirst,
                ];
            }

            if ($valueFirst === $valueSecond) {
                return [
                    'key' => $key,
                    'type' => self::UNCHANGED,
                    'value' => $valueFirst,
                ];
            }

            return [
                'key' => $key,
                'type' => self::CHANGED,
                'value1' => $valueFirst,
                'value2' => $valueSecond,
            ];
        }, $sortedArray);
    }

    private static function addingRootNode(array $value): array
    {
        return ['type' => 'root', 'children' => $value];
    }
}
