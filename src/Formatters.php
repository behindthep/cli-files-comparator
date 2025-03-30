<?php

namespace Diff\Comparator;

class Formatters
{
    public function makeFormat(string $difference, string $format): string
    {
        $result = "{\n$difference}\n";
        return $result;
    }
}
