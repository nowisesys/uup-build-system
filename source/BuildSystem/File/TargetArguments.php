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

namespace UUP\BuildSystem\File;

use InvalidArgumentException;

class TargetArguments
{
    /**
     * @var array The target arguments.
     */
    private array $arguments;

    /**
     * Constructor.
     * @param array $arguments
     */
    public function __construct(array $arguments = [])
    {
        $this->arguments = $arguments;
    }

    /**
     * Get target arguments.
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * Set target arguments.
     * @param array $arguments
     */
    public function setArguments(array $arguments): void
    {
        $this->arguments = $arguments;
    }

    /**
     * Parse arguments string into components.
     *
     * @param string $arguments The arguments string.
     */
    public function parseString(string $arguments): void
    {
        if ($this->isTargetIdentifier($arguments)) {
            $arguments = $this->getQuotedStringList($arguments);    // i.e. 'T1 T2' -> '"T1", "T2"'
        }

        $this->arguments = json_decode(sprintf("[%s]", $arguments));
    }

    /**
     * Check if arguments contains target identifier(s).
     *
     * @param string $arguments
     * @return bool
     */
    private function isTargetIdentifier(string $arguments): bool
    {
        if (strlen($arguments) == 0) {
            return false;
        }

        if (preg_match('/^\d+|true|false/', $arguments)) {
            return false;
        }
        if (preg_match('/^[\'"].*/', $arguments)) {
            return false;
        }

        return true;
    }

    /**
     * Quote arguments string.
     *
     * Returns a string where each term is quoted when called with a string where
     * each term are separated by whitespace and/or comma (',').
     *
     * For example: 'T1 T2, T3' -> '"T1", "T2", "T3'.
     *
     * @param string $arguments
     * @return string
     */
    private function getQuotedStringList(string $arguments): string
    {
        return sprintf(
            '"%s"', implode(
                '","', preg_split('/[,\s]+/', $arguments)
            )
        );
    }
}
