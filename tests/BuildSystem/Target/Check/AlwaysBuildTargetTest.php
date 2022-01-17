<?php

namespace UUP\Tests\BuildSystem\Target\Check;

use PHPUnit\Framework\TestCase;
use UUP\BuildSystem\Target\Check\AlwaysBuildTarget;

class MyTarget1 extends AlwaysBuildTarget
{
    public function __construct()
    {
        parent::__construct("/tmp/my-target1");
    }

    public function initialize()
    {
        touch("/tmp/my-target1");
    }

    public function cleanup()
    {
        if (file_exists($this->getLastTimePath())) {
            unlink($this->getLastTimePath());
        }
        if (file_exists($this->getLockFilePath())) {
            unlink($this->getLockFilePath());
        }
    }

    protected function perform(): void
    {
    }
}

class AlwaysBuildTargetTest extends TestCase
{
    public function testIsUpdated()
    {
        $this->assertFalse($this->target->isUpdated());

        $this->target->rebuild();
        $this->assertFalse($this->target->isUpdated());
    }

    protected function setUp(): void
    {
        $this->target = new MyTarget1();
        $this->target->initialize();
    }

    protected function tearDown(): void
    {
        $this->target->cleanup();
    }
}
