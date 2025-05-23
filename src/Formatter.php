<?php

namespace Gendiff\Formatter;

use function Gendiff\Formatters\Stylish\render as stylish;
use function Gendiff\Formatters\Json\render as json;
use function Gendiff\Formatters\Plain\render as plain;

function format(array $diff, string $format): string
{
    return match ($format) {
        'stylish' => stylish($diff),
        'json' => json($diff),
        'plain' => plain($diff),
        default => throw new \Exception(sprintf('Unknown data format: "%s"!', $format)),
    };
}
