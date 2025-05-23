<?php

namespace Gendiff\Formatter;

use function Gendiff\Formatters\{
    Stylish\render as stylish,
    Json\render as json,
    Plain\render as plain
};

function format(array $diff, string $format): string
{
    return match ($format) {
        'stylish' => stylish($diff),
        'json' => json($diff),
        'plain' => plain($diff),
        default => throw new \Exception(sprintf('Unknown data format: "%s"!', $format)),
    };
}
