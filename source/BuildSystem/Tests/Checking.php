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

use UUP\BuildSystem\Target\Check\DependencyCheckingTarget;

/**
 * Support class for examples and unit testing.
 * @author Anders Lövgren (Nowise Systems)
 */
class Checking extends DependencyCheckingTarget
{
    private string $name;
    private array $params;

    /**
     * Constructor.
     * @param string $name The target name.
     * @param array $params Optional target arguments.
     */
    public function __construct(string $name = "", ...$params)
    {
        parent::__construct("");

        $this->name = $name;
        $this->params = $params;
    }

    protected function perform(): void
    {
        printf("Called perform() on %s (%s: %s)\n", $this->getName(), $this->name, json_encode($this->params));
        usleep(250000);   // Simulate some work...
    }
}
