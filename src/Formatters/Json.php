<?php

namespace Diff\Comparator\Formatters;

class Json
{
    public static function jsonFormat(array $difference): string
    {
        return json_encode($difference, JSON_PRETTY_PRINT) . "\n";
    }
}
