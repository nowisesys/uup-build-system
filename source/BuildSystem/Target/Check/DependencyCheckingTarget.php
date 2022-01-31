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

namespace UUP\BuildSystem\Target\Check;

use UUP\BuildSystem\Target\Support\LockFileControlledTarget;

/**
 * Dependency checking target.
 *
 * Check if any dependency for this target has a newer last run file than
 * this target. Use this class for implementing targets that should only be
 * rebuilt when dependencies are newer.
 *
 * @author Anders Lövgren (Nowise Systems)
 */
abstract class DependencyCheckingTarget extends LockFileControlledTarget
{
    /**
     * @var array Array of timestamp files for dependencies.
     */
    private array $dependencies = [];

    /**
     * @inheritdoc
     */
    public function setDependencies(array $dependencies): void
    {
        parent::setDependencies($dependencies);

        foreach ($dependencies as $dependency) {
            $this->dependencies[] = $this->getFilepath(
                sprintf("%s.last", $dependency)
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function isUpdated(): bool
    {
        foreach ($this->dependencies as $dependency) {
            if (file_exists($dependency) && $this->getLastRun() < filemtime($dependency)) {
                return false;
            }
        }

        return $this->getLastRun() > 0;
    }

    /**
     * @inheritdoc
     */
    public function getType(): string
    {
        return "dependency-checking";
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return "Dependency checking target";
    }
}
