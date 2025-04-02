<?php

namespace Diff\Comparator;

use Symfony\Component\Yaml\Yaml;

class Parsers
{
    private static function getFileContent(string $pathToFile): string
    {
        if (is_file($pathToFile)) {
            return file_get_contents($pathToFile);
        }
        throw new \Exception("File not found", 1);
    }

    public static function parse(string $pathToFile): array
    {
        $content   = self::getFileContent($pathToFile);
        $extension = pathinfo($pathToFile, PATHINFO_EXTENSION);

        switch ($extension) {
            case 'json':
                return json_decode($content, true);
            case 'yml':
            case 'yaml':
                return Yaml::parse($content);
            default:
                throw new \Exception("Unsupported format of incoming file!", 1);
        }
    }
}
