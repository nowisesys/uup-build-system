<?php

namespace UUP\Tests\BuildSystem\Target\Check;

use PHPUnit\Framework\TestCase;
use UUP\BuildSystem\Target\Check\AlwaysBuildTarget;

class MyTarget1 extends AlwaysBuildTarget
{
    public function __construct()
    {
        parent::__construct("/tmp/my-target1");
        touch("/tmp/my-target1");
    }

    public function getDescription(): string
    {
        return "My Target 1";
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
        $target = new MyTarget1();
        $this->assertFalse($target->isUpdated());

        $target->rebuild();
        $this->assertFalse($target->isUpdated());
    }

    protected function tearDown(): void
    {
        $target = new MyTarget1();
        $target->cleanup();
    }
}
