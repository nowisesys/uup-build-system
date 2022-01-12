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
 * Support class for phony targets.
 * @author Anders Lövgren (Nowise Systems)
 */
class TargetPhony implements TargetInterface
{
    /**
     * @var string The target name.
     */
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function isUpdated(): bool
    {
        return false;
    }

    public function rebuild(): void
    {
        // ignore
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return sprintf("Phony target for %s", $this->name);
    }
}
