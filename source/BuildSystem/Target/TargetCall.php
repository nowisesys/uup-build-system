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
 * Run arbitrary PHP code.
 * @author Anders Lövgren (Nowise Systems)
 */
class TargetCall extends TargetBase
{
    /**
     * @var string The source code.
     */
    private string $code;

    /**
     * Constructor.
     * @param string $code The source code.
     */
    public function __construct(string $code = "")
    {
        $this->code = $code;
    }

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
    public function rebuild(): void
    {
        eval($this->code);
    }

    /**
     * @inheritdoc
     */
    public function getType(): string
    {
        return "call";
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return "Evaluate PHP code";
    }
}
