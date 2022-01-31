<?php

namespace UUP\Tests\BuildSystem\Target\Support;

use PHPUnit\Framework\TestCase;
use UUP\BuildSystem\Target\Support\LockFileControlledTarget;

class MyTarget0 extends LockFileControlledTarget
{
    private int $built = 0;

    public function __construct()
    {
        parent::__construct("/tmp/my-target0");
        parent::setName("my-target0");
    }

    public function getBuilt(): int
    {
        return $this->built;
    }

    public function initialize()
    {
        touch("/tmp/my-target0");
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
        $this->built++;
    }
}

class LockFileControlledTargetTest extends TestCase
{
    public function testGetName()
    {
        $this->assertEquals("my-target0", $this->target->getName());
    }

    public function testGetType()
    {
        $this->assertEquals("lockfile", $this->target->getType());
    }

    public function testGetFilename()
    {
        $this->assertEquals("/tmp/my-target0", $this->target->getFilename());
    }

    public function testGetLastTimePath()
    {
        $this->assertNotEmpty($this->target->getLastTimePath());
        $this->assertEquals("my-target0.last", basename($this->target->getLastTimePath()));
    }

    public function testSetLastTimePath()
    {
        $this->target->setLastTimePath("/tmp/my-target.last");
        $this->assertNotEmpty($this->target->getLastTimePath());
        $this->assertEquals("/tmp/my-target.last", $this->target->getLastTimePath());
    }

    public function testGetLockFilePath()
    {
        $this->assertNotEmpty($this->target->getLockFilePath());
        $this->assertEquals("my-target0.lock", basename($this->target->getLockFilePath()));
    }

    public function testSetLockFilePath()
    {
        $this->target->setLockFilePath("/tmp/my-target.lock");
        $this->assertNotEmpty($this->target->getLockFilePath());
        $this->assertEquals("/tmp/my-target.lock", $this->target->getLockFilePath());
    }

    public function testGetLastRun()
    {
        $this->assertEquals(0, $this->target->getLastRun());

        $this->target->rebuild();
        $this->assertEquals(time(), $this->target->getLastRun());
    }

    public function testIsLocked()
    {
        $this->assertFalse($this->target->isLocked());
    }

    public function testIsUpdated()
    {
        $this->assertFalse($this->target->isUpdated());

        $this->target->rebuild();
        $this->assertTrue($this->target->isUpdated());
    }

    public function testRebuild()
    {
        $this->target->rebuild();
        $this->assertEquals(1, $this->target->getBuilt());

        $this->target->rebuild();
        $this->assertEquals(2, $this->target->getBuilt());

        if (!$this->target->isUpdated()) {
            $this->target->rebuild();
        }

        $this->assertEquals(2, $this->target->getBuilt());
    }

    protected function setUp(): void
    {
        $this->target = new MyTarget0();
        $this->target->initialize();
    }

    protected function tearDown(): void
    {
        $this->target->cleanup();
    }
}
