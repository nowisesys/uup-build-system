<?php

/*
 * Copyright (C) 2022 Anders Lövgren (Nowise Systems).
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

namespace UUP\BuildSystem\Target;

/**
 * Abstract base class for targets.
 * @author Anders Lövgren (Nowise Systems)
 */
abstract class TargetBase implements TargetInterface
{
    /**
     * @var string The target name.
     */
    private string $name = "";

    /**
     * @var array The target dependencies.
     */
    private array $dependencies = [];

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set target name.
     * @param string $name The target name.
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get list of dependency names.
     * @return array
     */
    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    /**
     * Set list of dependency names.
     * @param array $dependencies The dependency list.
     */
    public function setDependencies(array $dependencies): void
    {
        $this->dependencies = $dependencies;
    }

    /**
     * Get environment variable.
     *
     * When using the file reader, then any variable not reserved will be injected
     * into the environment. This method provides a convenience method for accessing
     * those variables with default value if missing.
     *
     * @param string $name The variable name.
     * @param mixed $default
     * @return mixed
     */
    public function getEnvironment(string $name, $default = "")
    {
        return $_ENV[$name] ?? $default;
    }

    /**
     * Set environment variable.
     *
     * @param string $name The variable name.
     * @param mixed $value The variable value.
     */
    public function setEnvironment(string $name, $value): void
    {
        $_ENV[$name] = $value;
    }

    /**
     * @inheritdoc
     */
    public function getType(): string
    {
        return "abstract";
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return "Abstract target base";
    }
}
