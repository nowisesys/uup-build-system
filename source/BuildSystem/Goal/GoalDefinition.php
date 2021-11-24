<?php

declare(strict_types=1);

namespace UUP\BuildSystem\Goal;

use UUP\BuildSystem\Target\TargetInterface;

class GoalDefinition
{
    private ?TargetInterface $target;
    private array $dependencies;

    public function __construct(TargetInterface $target = null, array $dependencies = [])
    {
        $this->target = $target;
        $this->dependencies = $dependencies;
    }

    public function hasDependencies(): bool
    {
        return count($this->dependencies) != 0;
    }

    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    public function setDependencies(array $dependencies): void
    {
        $this->dependencies = $dependencies;
    }

    public function addDependency(string $name): void
    {
        $this->dependencies[] = $name;
    }

    public function getTarget(): ?TargetInterface
    {
        return $this->target;
    }

    public function setTarget(TargetInterface $target): void
    {
        $this->target = $target;
    }
}
