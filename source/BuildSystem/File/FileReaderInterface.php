<?php

declare(strict_types=1);

namespace UUP\BuildSystem\File;

use UUP\BuildSystem\Node\DependencyTree;

interface FileReaderInterface
{
    public function getDependencyTree(): DependencyTree;

    public function setDependencyTree(DependencyTree $dependencyTree): void;

    public function addDependencies(string $filename): void;
}
