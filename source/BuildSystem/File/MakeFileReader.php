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
 * The GNU makefile reader.
 * @author Anders Lövgren (Nowise Systems)
 */
class MakeFileReader extends FileReaderBase implements FileReaderInterface
{
    private MakeFileRule $rule;

    /**
     * @inheritdoc
     */
    public function addDependencies(string $filename): void
    {
        if (!is_readable($filename)) {
            throw new RuntimeException("The dependency file is not readable");
        }

        $content = $this->getMakeContent($filename);

        if (array_key_exists('namespace', $content)) {
            $this->setNamespace($content['namespace']);
        }
        if (array_key_exists('targets', $content)) {
            $this->addTargets($content['targets']);
        }
    }

    /**
     * Read makefile content.
     *
     * Matches assignment, rule definitions and target commands. Creates rule object
     * that are appended to the result array.
     *
     * @param string $filename The filename path.
     * @return array
     */
    private function getMakeContent(string $filename): array
    {
        $result = [
            'targets' => []
        ];
        $handle = fopen($filename, "r");

        while ($line = fgets($handle)) {
            $line = trim($line, " \n");

            if (empty($line) || preg_match('/^#/', $line)) {
                continue;
            } elseif (preg_match('/\s*(\w+)\s*:=\s*(.*)\s*/', $line, $matches)) {
                $this->setOptions($matches[1], $matches[2]);
            } elseif (preg_match('/^(\w+)\s*:\s*(.*)/', $line, $matches)) {
                $this->setRule($matches[1], $matches[2]);
            } elseif (preg_match('/^\t(.*)\("(.*)"\)/', $line, $matches)) {
                $this->setTarget($matches[1], $matches[2]);
                $result['targets'][$this->rule->getName()] = $this->rule->getDefinition();
            }
        }

        fclose($handle);

        return $result;
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

    /**
     * Set option from makefile content.
     * @param string $key The option key.
     * @param string $value The option value.
     */
    private function setOptions(string $key, string $value): void
    {
        switch ($key) {
            case 'NAMESPACE':
                $this->setNamespace($value);
                break;
            default:
                // ignored right now
        }
    }

    /**
     * Begins a new makefile rule object.
     * @param string $goal The goal name.
     * @param string $depends The rule dependencies.
     */
    private function setRule(string $goal, string $depends): void
    {
        $this->rule = new MakeFileRule($goal);
        $this->rule->setDependencies(
            array_filter(
                preg_split('/[\s]+/', $depends)
            )
        );
    }

    /**
     * Set target command.
     * @param string $class The class to execute.
     * @param string $arguments Optional argument for constructor.
     */
    private function setTarget(string $class, string $arguments): void
    {
        $this->rule->setClass($class);
        $this->rule->setArguments($arguments);
    }
}
