<?php

declare(strict_types=1);

namespace UUP\BuildSystem\File;

class MakeFileRule
{
    private array $definition = [
        'name' => '',
        'class' => '',
        'arguments' => '',
        'dependencies' => []
    ];

    public function __construct(string $name)
    {
        $this->setName($name);
    }

    public function getName(): string
    {
        return $this->definition['name'];
    }

    public function setName(string $name): void
    {
        $this->definition['name'] = $name;
    }

    public function setClass(string $class): void
    {
        $this->definition['class'] = $class;
    }

    public function setArguments(string $arguments): void
    {
        $this->definition['arguments'] = $arguments;
    }

    public function setDependencies(array $dependencies): void
    {
        $this->definition['dependencies'] = $dependencies;
    }

    public function getDefinition(): array
    {
        return $this->definition;
    }
}
