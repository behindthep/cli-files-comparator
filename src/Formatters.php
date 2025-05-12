<?php

namespace Gendiff;

use Gendiff\Formatters\{
    Stylish,
    Plain,
    Json,
};

class Formatters
{
    public static function makeFormat(array $diff, string $format): string
    {
        return match ($format) {
            'stylish' => Stylish::stylishFormat($diff),
            'plain' => Plain::plainFormat($diff),
            'json' => Json::jsonFormat($diff),
            default => throw new \Exception("Unknown format '$format'!", 1)
        };
    }
}
