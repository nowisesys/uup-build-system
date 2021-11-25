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

use UUP\BuildSystem\Goal\GoalDefinition;

/**
 * Support class for examples and unit testing.
 * @author Anders Lövgren (Nowise Systems)
 */
class Definition extends GoalDefinition
{
    /**
     * Constructor.
     * @param string $name The target name.
     * @param array $dependencies Optional list of goal dependencies.
     */
    public function __construct(string $name, array $dependencies = [])
    {
        parent::__construct(new Target($name), $dependencies);
    }
}
