<?php

declare(strict_types=1);

namespace UUP\BuildSystem\File;

use ReflectionException;
use RuntimeException;

class JsonFileReader extends FileReaderBase implements FileReaderInterface
{
    public function __construct()
    {
        if (!extension_loaded("json")) {
            throw new RuntimeException("The JSON extension is not loaded");
        }

        parent::__construct();
    }

    /**
     * @throws ReflectionException
     */
    public function addDependencies(string $filename): void
    {
        if (!is_readable($filename)) {
            throw new RuntimeException("The dependency file is not readable");
        }

        $content = $this->getJsonContent($filename);

        if (array_key_exists('namespace', $content)) {
            $this->setNamespace($content['namespace']);
        }
        if (array_key_exists('targets', $content)) {
            $this->addTargets($content['targets']);
        }
    }

    private function getJsonContent(string $filename): array
    {
        return json_decode($this->getFileContent($filename), true);
    }

    private function getFileContent(string $filename): string
    {
        return file_get_contents($filename);
    }

    /**
     * @throws ReflectionException
     */
    private function addTargets(array $targets): void
    {
        foreach ($targets as $target => $options) {
            $definition = $this->getGoalDefinition($target, $options);
            $this->getDependencyTree()->addDefinition($definition);
        }
    }
}
