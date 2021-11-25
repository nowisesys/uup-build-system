<?php

/*
 * Copyright (C) 2021 Anders Lövgren (Nowise Systems).
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types=1);

namespace UUP\BuildSystem\File;

use ReflectionException;
use RuntimeException;

/**
 * The JSON file reader.
 * @author Anders Lövgren (Nowise Systems)
 */
class JsonFileReader extends FileReaderBase implements FileReaderInterface
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        if (!extension_loaded("json")) {
            throw new RuntimeException("The JSON extension is not loaded");
        }

        parent::__construct();
    }

    /**
     * @inheritdoc
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

    /**
     * Get array from JSON file.
     * @param string $filename The filename path.
     * @return array
     */
    private function getJsonContent(string $filename): array
    {
        return json_decode($this->getFileContent($filename), true);
    }

    /**
     * Get file content.
     * @param string $filename The filename path.
     * @return string
     */
    private function getFileContent(string $filename): string
    {
        return file_get_contents($filename);
    }

    /**
     * Add goal definitions to dependency tree.
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
