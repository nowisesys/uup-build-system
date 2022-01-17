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

namespace UUP\BuildSystem\Tests;

use UUP\BuildSystem\Target\TargetInterface;

/**
 * Support class for examples and unit testing.
 * @author Anders Lövgren (Nowise Systems)
 */
class Target implements TargetInterface
{
    private string $name;
    private bool $updated = false;
    private array $params;

    /**
     * Constructor.
     * @param string $name The target name.
     * @param array $params Optional target arguments.
     */
    public function __construct(string $name, ...$params)
    {
        $this->name = $name;
        $this->params = $params;
    }

    /**
     * @inheritdoc
     */
    public function isUpdated(): bool
    {
        printf("Called isUpdated() on %s (updated=%b) (%s)\n", $this->name, $this->updated, json_encode($this->params));
        return $this->updated;
    }

    /**
     * @inheritdoc
     */
    public function rebuild(): void
    {
        printf("Called rebuild() on %s (updated=%b)\n", $this->name, $this->updated);
        $this->updated = true;
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function getType(): string
    {
        return "test";
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return sprintf("Example target %s", $this->name);
    }
}
