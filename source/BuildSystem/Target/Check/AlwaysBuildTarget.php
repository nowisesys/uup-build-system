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
 * Always rebuilt target.
 *
 * Target is always rebuilt whether source file is newer than last execution
 * or not. Use this class for targets that should always be rebuilt, unless
 * already executing.
 *
 * @author Anders Lövgren (Nowise Systems)
 */
abstract class AlwaysBuildTarget extends LockFileControlledTarget
{
    /**
     * @inheritdoc
     */
    public function isUpdated(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getType(): string
    {
        return "always-build";
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return "Always rebuild target";
    }
}
