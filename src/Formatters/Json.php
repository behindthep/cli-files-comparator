<?php

namespace Gendiff\Formatters;

class Json
{
    public static function render(array $data): string
    {
        return json_encode($data, JSON_THROW_ON_ERROR);
    }
}
