<?php

declare(strict_types=1);

namespace UUP\BuildSystem\Tests\Implicit;

use UUP\BuildSystem\Tests\Target;

class T2 extends Target
{
    public function __construct()
    {
        parent::__construct("T2");
    }
}