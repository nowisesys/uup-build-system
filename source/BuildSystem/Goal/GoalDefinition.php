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

namespace UUP\BuildSystem\Goal;

use UUP\BuildSystem\Target\TargetInterface;

/**
 * The goal definition.
 *
 * Used for constructing the dependency tree. A goal definition consists of the
 * target to execute and an optional list of other goals that should be ful-filled
 * before executing the target.
 *
 * The dependencies are just a list (array) of strings used for lookup of other goals
 * from the goal registry. When using the dependency tree, all lookup from the registry
 * is handled transparent.
 *
 * @author Anders Lövgren (Nowise Systems)
 */
class GoalDefinition
{
    private ?TargetInterface $target;
    private array $dependencies;

    /**
     * Constructor.
     * @param TargetInterface|null $target The goal target.
     * @param array $dependencies Optional list of dependencies.
     */
    public function __construct(TargetInterface $target = null, array $dependencies = [])
    {
        $this->target = $target;
        $this->dependencies = $dependencies;
    }

    /**
     * Return thue if goal has dependencies.
     * @return bool
     */
    public function hasDependencies(): bool
    {
        return count($this->dependencies) != 0;
    }

    /**
     * Get list of dependencies.
     * @return array
     */
    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    /**
     * Set dependencies for this goal.
     * @param array $dependencies
     */
    public function setDependencies(array $dependencies): void
    {
        $this->dependencies = $dependencies;
    }

    /**
     * Add dependency for goal.
     * @param string $name The goal to depend on.
     */
    public function addDependency(string $name): void
    {
        $this->dependencies[] = $name;
    }

    /**
     * Get goal target.
     * @return TargetInterface|null
     */
    public function getTarget(): ?TargetInterface
    {
        return $this->target;
    }

    /**
     * Set goal target.
     * @param TargetInterface $target
     */
    public function setTarget(TargetInterface $target): void
    {
        $this->target = $target;
    }
}
