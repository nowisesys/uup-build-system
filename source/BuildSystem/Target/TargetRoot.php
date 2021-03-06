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
 * The root target.
 *
 * Support class for dependency tree providing the root node.
 * @author Anders Lövgren (Nowise Systems)
 */
class TargetRoot implements TargetInterface
{
    /**
     * @inheritdoc
     */
    public function isUpdated(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function rebuild(): void
    {
        // ignore
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return "root";
    }

    /**
     * @inheritdoc
     */
    public function getType(): string
    {
        return "root";
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return "The target for root node";
    }
}
