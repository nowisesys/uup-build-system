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

namespace UUP\BuildSystem\File;

use ReflectionException;
use UUP\BuildSystem\Node\DependencyTree;

/**
 * The interface for file readers.
 * @author Anders Lövgren (Nowise Systems)
 */
interface FileReaderInterface
{
    /**
     * Get dependency tree.
     * @return DependencyTree
     */
    public function getDependencyTree(): DependencyTree;

    /**
     * Set dependency tree.
     * @param DependencyTree $dependencyTree
     */
    public function setDependencyTree(DependencyTree $dependencyTree): void;

    /**
     * Read dependencies from file.
     * @param string $filename The filename path.
     * @throws ReflectionException
     */
    public function addDependencies(string $filename): void;

    /**
     * Get current namespace.
     * @return string
     */
    public function getNamespace(): string;

    /**
     * Set current namespace.
     * @param string $namespace
     */
    public function setNamespace(string $namespace): void;
}
