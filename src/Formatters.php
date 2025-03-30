<?php

namespace Diff\Comparator;

use Diff\Comparator\Formatters\Stylish;

class Formatters
{
    public function makeFormat(array $difference, string $format): string
    {
        switch ($format) {
            case 'stylish':
                $stylish = new Stylish();
                $formatted = $stylish->stylishFormat($difference);
                return $formatted;
            default:
                exit("Unknown format '$format'.\n");
        }
    }
}
