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

    public static function parse(string $pathToFile): array|string
    {
        $content   = self::getFileContent($pathToFile);
        $extension = pathinfo($pathToFile, PATHINFO_EXTENSION);

        $parsedFile = match ($extension) {
            'json'        => json_decode($content, true),
            'yml', 'yaml' => Yaml::parse($content),
            default       => exit("Unsupported format '$extension' of incoming file.\n"),
        };

        return $parsedFile;
    }
}
