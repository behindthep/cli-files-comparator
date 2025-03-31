<?php

namespace Diff\Comparator;

use Diff\Comparator\Formatters\{Stylish, Plain, Json};

class Formatters
{
    public static function makeFormat(array $difference, string $format): string
    {
        return match ($format) {
            'stylish' => Stylish::stylishFormat($difference),
            'plain'   => Plain::plainFormat($difference),
            'json'    => Json::jsonFormat($difference),
            default   => throw new \Exception("Unknown format '$format'!", 1)
        };
    }
}
