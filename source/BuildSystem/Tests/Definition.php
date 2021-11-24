<?php

declare(strict_types=1);

namespace UUP\BuildSystem\Tests;

use UUP\BuildSystem\Goal\GoalDefinition;

class Definition extends GoalDefinition
{
    public function __construct(string $name, array $dependencies = [])
    {
        parent::__construct(new Target($name), $dependencies);
    }
}
