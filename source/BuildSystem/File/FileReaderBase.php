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

use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use UUP\BuildSystem\Goal\GoalDefinition;
use UUP\BuildSystem\Node\DependencyTree;
use UUP\BuildSystem\Target\TargetCall;
use UUP\BuildSystem\Target\TargetPhony;
use UUP\BuildSystem\Target\TargetShell;

/**
 * Base class for file readers.
 *
 * Support class for GNU makefile and JSON file readers. Provides methods for managing
 * the namespace (for class loader) and target dependency tree.
 *
 * @author Anders Lövgren (Nowise Systems)
 */
abstract class FileReaderBase implements FileReaderInterface
{
    private string $namespace = "";
    private DependencyTree $dependencyTree;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->dependencyTree = new DependencyTree();
    }

    /**
     * @inheritdoc
     */
    public function getDependencyTree(): DependencyTree
    {
        return $this->dependencyTree;
    }

    /**
     * @inheritdoc
     */
    public function setDependencyTree(DependencyTree $dependencyTree): void
    {
        $this->dependencyTree = $dependencyTree;
    }

    /**
     * @inheritdoc
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @inheritdoc
     */
    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    /**
     * @inheritdoc
     */
    public function setDebug(bool $enable = true): void
    {
        $_ENV['PBS_MAKE_DEBUG'] = $enable;
    }

    /**
     * @inheritdoc
     */
    public function setVerbose(bool $enable = true): void
    {
        $_ENV['PBS_MAKE_VERBOSE'] = $enable;
    }

    /**
     * @inheritdoc
     * @throws ReflectionException
     */
    public function addPhonyTargets(array $names): void
    {
        foreach ($names as $name) {
            $definition = $this->getGoalDefinition($name, [
                'class' => 'Phony',
                'arguments' => [$name],
                'dependencies' => []
            ]);
            $this->dependencyTree->addDefinition($definition);
        }
    }

    /**
     * Creates a goal definition object.
     * @throws ReflectionException
     */
    protected function getGoalDefinition(string $target, array $options): GoalDefinition
    {
        if (!isset($options['dependencies'])) {
            throw new InvalidArgumentException("The dependencies is missing in goal definition");
        }
        if (!isset($options['class'])) {
            $options['class'] = $target;
        }
        if (!isset($options['arguments'])) {
            $options['arguments'] = $options['dependencies'];
        }

        if ($options['class'] == "Shell") {
            $options['class'] = TargetShell::class;
        }
        if ($options['class'] == "Phony") {
            $options['class'] = TargetPhony::class;
        }
        if ($options['class'] == "Call") {
            $options['class'] = TargetCall::class;
        }

        if (empty($options['arguments']) || $options['arguments'][0] != $target) {
            array_unshift($options['arguments'], $target);
        }

        if ($this->getDependencyTree()->getRegistry()->hasNode($target)) {
            return new GoalDefinition($this->getDependencyTree()
                ->getRegistry()
                ->getNode($target)
                ->getTarget(), $options['dependencies']
            );
        }

        /** @noinspection PhpParamsInspection */

        return new GoalDefinition(
            $this->getReflectionClass($options['class'])
                ->newInstance(...$options['arguments']),
            $options['dependencies']
        );
    }

    /**
     * Get fully qualified classname using current namespace.
     * @param string $class
     * @return string
     */
    protected function getClassName(string $class): string
    {
        if (strstr($class, '\\')) {
            return $class;
        } else {
            return sprintf("%s\%s", $this->getNamespace(), $class);
        }
    }

    /**
     * Get reflection class.
     * @throws ReflectionException
     */
    protected function getReflectionClass(string $class): ReflectionClass
    {
        return new ReflectionClass($this->getClassName($class));
    }

    /**
     * Set content parsed from input file.
     *
     * @param array $content The content array.
     * @throws ReflectionException
     */
    protected function setDependencies(array $content): void
    {
        if (array_key_exists('verbose', $content)) {
            $this->setVerbose($content['verbose']);
        }
        if (array_key_exists('debug', $content)) {
            $this->setDebug($content['debug']);
        }
        if (array_key_exists('phony', $content)) {
            $this->addPhonyTargets($content['phony']);
        }
        if (array_key_exists('namespace', $content)) {
            $this->setNamespace($content['namespace']);
        }
        if (array_key_exists('targets', $content)) {
            $this->addTargets($content['targets']);
        }
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
