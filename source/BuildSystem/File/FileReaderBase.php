<?php

declare(strict_types=1);

namespace UUP\BuildSystem\File;

use ReflectionClass;
use ReflectionException;
use UUP\BuildSystem\Goal\GoalDefinition;
use UUP\BuildSystem\Node\DependencyTree;

abstract class FileReaderBase
{
    private string $namespace = "";
    private DependencyTree $dependencyTree;

    public function __construct()
    {
        $this->dependencyTree = new DependencyTree();
    }

    public function getDependencyTree(): DependencyTree
    {
        return $this->dependencyTree;
    }

    public function setDependencyTree(DependencyTree $dependencyTree): void
    {
        $this->dependencyTree = $dependencyTree;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @throws ReflectionException
     */
    protected function getGoalDefinition(string $target, array $options): GoalDefinition
    {
        /** @noinspection PhpParamsInspection */

        return new GoalDefinition(
            $this->getReflectionClass($options['class'])
                ->newInstance($options['arguments']),
            $options['dependencies']
        );
    }

    protected function getClassName(string $class): string
    {
        if (strstr($class, '\\')) {
            return $class;
        } else {
            return sprintf("%s\%s", $this->getNamespace(), $class);
        }
    }

    /**
     * @throws ReflectionException
     */
    protected function getReflectionClass(string $class): ReflectionClass
    {
        return new ReflectionClass($this->getClassName($class));
    }

}
