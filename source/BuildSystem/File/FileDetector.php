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

use DirectoryIterator;
use Generator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class FileDetector
{
    /**
     * @var string The directory root.
     */
    private string $directory;

    /**
     * @var bool Enable recursive mode.
     */
    private bool $recursive = false;

    /**
     * Constructor.
     * @param string $directory The directory to scan.
     */
    public function __construct(string $directory = ".")
    {
        $this->directory = $directory;
    }

    /**
     * Get directory root.
     * @return string
     */
    public function getDirectory(): string
    {
        return $this->directory;
    }

    /**
     * Set directory root.
     * @param string $directory
     */
    public function setDirectory(string $directory): void
    {
        $this->directory = $directory;
    }

    /**
     * Enable recursive mode.
     * @param bool $enable
     */
    public function setRecursive(bool $enable = true): void
    {
        $this->recursive = $enable;
    }

    /**
     * Get matching file paths generator.
     * @return Generator
     */
    public function getGenerator(): Generator
    {
        foreach ($this->getRegexIterator() as $info) {
            yield $info->getPathname();
        }
    }

    /**
     * Get matching file paths array.
     * @return array
     */
    public function getMakefiles(): array
    {
        return iterator_to_array($this->getGenerator());
    }

    /**
     * The directory iterator.
     * @return RegexIterator
     */
    private function getRegexIterator(): RegexIterator
    {
        if ($this->recursive) {
            return $this->getRecursiveModeIterator();
        } else {
            return $this->getDirectoryModeIterator();
        }
    }

    /**
     * The recursive directory iterator.
     * @return RegexIterator
     */
    private function getRecursiveModeIterator(): RegexIterator
    {
        return new RegexIterator(
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($this->directory)
            ),
            '/\/(build\.(make|json)|makefile(\.txt)*|.*\.pbs)$/',
            RegexIterator::MATCH
        );
    }

    /**
     * Get current directory iterator.
     * @return RegexIterator
     */
    private function getDirectoryModeIterator(): RegexIterator
    {
        return new RegexIterator(
            new DirectoryIterator($this->directory),
            '/^(build\.(make|json)|makefile(\.txt)*|.*\.pbs)$/',
            RegexIterator::MATCH
        );
    }
}
