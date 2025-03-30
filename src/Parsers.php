<?php

namespace Diff\Comparator;

use Symfony\Component\Yaml\Yaml;

class Parsers
{
    private function getFileContent(string $pathToFile): string
    {
        $content = is_file($pathToFile) ? file_get_contents($pathToFile) : exit("File '$pathToFile' not found.\n");

        return $content;
    }

    public function parse(string $pathToFile): array
    {
        $content = $this->getFileContent($pathToFile);
        $extension = pathinfo($pathToFile, PATHINFO_EXTENSION);

        switch ($extension) {
            case 'json':
                $parsedFile = json_decode($content, true);
                break;
            case 'yml':
            case 'yaml':
                $parsedFile = Yaml::parse($content);
                break;
            default:
                exit("\nUnsupported format '$extension' of incoming file.\n");
        }

        echo "\nParsed Content:\n";
        var_export($parsedFile);
        echo "\n";

        return $parsedFile;
    }
}
