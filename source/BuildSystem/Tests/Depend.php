<?php

declare(strict_types=1);

namespace UUP\BuildSystem\Tests;

use UUP\BuildSystem\Node\DependencyNode;

class Depend extends DependencyNode
{
    public function __construct(string $name)
    {
        parent::__construct(new Target($name));
    }
}
