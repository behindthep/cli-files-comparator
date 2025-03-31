<?php

namespace Diff\Comparator;

use Diff\Comparator\Formatters\Stylish;

class Formatters
{
    public static function makeFormat(array $difference, string $format): string
    {
        switch ($format) {
            case 'stylish':
                $formatted = Stylish::stylishFormat($difference);
                return $formatted;
            default:
                exit("Unknown format '$format'.\n");
        }
    }
}
