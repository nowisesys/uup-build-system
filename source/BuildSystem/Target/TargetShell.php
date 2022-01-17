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

use RuntimeException;

/**
 * Execute commands in a system shell.
 * @author Anders Lövgren (Nowise Systems)
 */
class TargetShell implements TargetInterface
{
    /**
     * @var string The target name.
     */
    private string $name;

    /**
     * @var string The commands to execute.
     */
    private string $commands;

    /**
     * Constructor.
     *
     * @param string $name The target name.
     * @param string $commands The commands to execute.
     */
    public function __construct(string $name = "", string $commands = "")
    {
        $this->name = $name;
        $this->commands = $commands;
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
        if (preg_match('/@\((.*)\)/', $this->commands, $matches)) {
            if (system(sprintf("(%s) >& /dev/null", $matches[1]), $code) === false) {
                throw new RuntimeException(sprintf(
                    "Failed execute '%s' in system shell (silent:%d)", $matches[1], $code
                ));
            }
        } else {
            if (system($this->commands, $code) === false) {
                throw new RuntimeException(sprintf(
                    "Failed execute '%s' in system shell (verbose:%d)", $this->commands, $code
                ));
            }
        }
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
        return "shell";
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return "Execute commands in a system shell";
    }
}
