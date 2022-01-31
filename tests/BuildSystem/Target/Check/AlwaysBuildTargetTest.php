<?php

namespace UUP\Tests\BuildSystem\Target\Check;

use PHPUnit\Framework\TestCase;
use UUP\BuildSystem\Target\Check\AlwaysBuildTarget;

class MyTarget1 extends AlwaysBuildTarget
{
    public function __construct()
    {
        parent::__construct("/tmp/my-target1");
        parent::setName("my-target1");
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
        if (file_exists($this->getFilename())) {
            unlink($this->getFilename());
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

    public function testGetType()
    {
        $this->assertEquals("always-build", $this->target->getType());
    }

    public function testGetDescription()
    {
        $this->assertEquals("Always rebuild target", $this->target->getDescription());
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
