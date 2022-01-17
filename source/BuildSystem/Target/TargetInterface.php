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

namespace UUP\BuildSystem\Target;

/**
 * The target interface.
 * @author Anders Lövgren (Nowise Systems)
 */
interface TargetInterface
{
    /**
     * Returns true of target is up-to-date.
     * @return bool
     */
    public function isUpdated(): bool;

    /**
     * Rebuild target if not updated.
     */
    public function rebuild(): void;

    /**
     * Get target name.
     * @return string
     */
    public function getName(): string;

    /**
     * Get target type.
     * @return string
     */
    public function getType(): string;

    /**
     * Get target description.
     * @return string
     */
    public function getDescription(): string;
}
