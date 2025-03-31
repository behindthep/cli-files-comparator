<?php

namespace Diff\Comparator;

use Diff\Comparator\Formatters\{Stylish, Plain};

class Formatters
{
    public static function makeFormat(array $difference, string $format): string
    {
        $formattedDiff = match ($format) {
            'stylish' => Stylish::stylishFormat($difference),
            'plain'   => Plain::plainFormat($difference),
            default   => exit("Unknown format '$format'.\n")
        };

        return $formattedDiff;
    }
}
