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

/**
 * The makefile rule.
 * @author Anders Lövgren (Nowise Systems)
 */
class MakeFileRule
{
    private array $definition = [
        'name' => '',
        'class' => '',
        'arguments' => [],
        'dependencies' => []
    ];

    /**
     * Constructor.
     * @param string $name The goal name.
     */
    public function __construct(string $name = "")
    {
        $this->setName($name);
    }

    /**
     * Get goal name.
     * @return string
     */
    public function getName(): string
    {
        return $this->definition['name'];
    }

    /**
     * Set goal name.
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->definition['name'] = $name;
    }

    /**
     * Set class to execute.
     * @param string $class
     */
    public function setClass(string $class): void
    {
        $this->definition['class'] = $class;
    }

    /**
     * Set constructor argument for class.
     * @param array $arguments
     */
    public function setArguments(array $arguments): void
    {
        $this->definition['arguments'] = $arguments;
    }

    /**
     * Set dependencies.
     *
     * The dependencies are a list of other goals that needs to be ful-filled before
     * the current rule can be considered. This list is equivalent with the right-hand
     * side list of goals in a makefile rule.
     *
     * @param array $dependencies
     */
    public function setDependencies(array $dependencies): void
    {
        $this->definition['dependencies'] = $dependencies;
    }

    /**
     * Get rule definition.
     * @return array
     */
    public function getDefinition(): array
    {
        return $this->definition;
    }
}
