<?php

namespace Gendiff;

use Gendiff\Formatters\{
    Stylish,
    Plain,
    Json,
};

class Formatter
{
    public static function format(array $diff, string $format): string
    {
        return match ($format) {
            'stylish' => Stylish::render($diff),
            'json' => Json::render($diff),
            'plain' => Plain::render($diff),
            default => throw new \Exception(sprintf('Unknown data format: "%s"!', $format)),
        };
    }
}
