<?php

namespace UUP\Tests\BuildSystem\Target\Check;

use PHPUnit\Framework\TestCase;
use UUP\BuildSystem\Target\Check\UpdateModifiedTarget;

class MyTarget3 extends UpdateModifiedTarget
{
    public function __construct()
    {
        parent::__construct("/tmp/my-target3");
        parent::setName("my-target3");
    }

    public function initialize()
    {
        touch("/tmp/my-target3");
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

class UpdateModifiedTargetTest extends TestCase
{
    public function testIsUpdated()
    {
        $this->assertFalse($this->target->isUpdated());

        $this->target->rebuild();
        $this->assertTrue($this->target->isUpdated());

        touch($this->target->getFilename());
        touch($this->target->getLastTimePath(), time() - 1);      // Fake rebuild one second ago

        $this->assertFalse($this->target->isUpdated());

        $this->target->rebuild();
        $this->assertTrue($this->target->isUpdated());
    }

    public function testGetType()
    {
        $this->assertEquals("update-modified", $this->target->getType());
    }

    public function testGetDescription()
    {
        $this->assertEquals("Update when modified target", $this->target->getDescription());
    }

    protected function setUp(): void
    {
        $this->target = new MyTarget3();
        $this->target->initialize();
    }

    protected function tearDown(): void
    {
        $this->target->cleanup();
    }
}
