<?php

declare(strict_types=1);

namespace UUP\BuildSystem\Tests\Implicit;

use UUP\BuildSystem\Tests\Target;

class T4 extends Target
{
    public function __construct(...$params)
    {
        parent::__construct("T4", ...$params);
    }
}
