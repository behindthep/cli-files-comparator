<?php

namespace Gendiff;

use Symfony\Component\Yaml\Yaml;

class Parser
{
    private function getFileData(string $path): string
    {
        $absolutePath = realpath($path);
        if (!file_exists($absolutePath)) {
            throw new \Exception("File does not exist", 1);
        }
        
        return file_get_contents($path);        
    }

    public static function parse(string $path): array
    {
        $instance = new self();
        $content = $instance->getFileData($path);
        $extension = pathinfo($path, PATHINFO_EXTENSION);

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
