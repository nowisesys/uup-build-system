<?php

namespace UUP\Tests\BuildSystem\Target\Support;

use PHPUnit\Framework\TestCase;
use UUP\BuildSystem\Target\Support\LockFileControlledTarget;

class MyTarget0 extends LockFileControlledTarget
{
    private int $built = 0;

    public function __construct()
    {
        parent::__construct("/tmp/my-target");
        touch("/tmp/my-target");
    }

    public function getDescription(): string
    {
        return "My Target";
    }

    public function getBuilt(): int
    {
        return $this->built;
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
        $this->built++;
    }
}

class LockFileControlledTargetTest extends TestCase
{
    public function testGetName()
    {
        $target = new MyTarget0();
        $this->assertEquals("my-target", $target->getName());
    }

    public function testGetFilename()
    {
        $target = new MyTarget0();
        $this->assertEquals("/tmp/my-target", $target->getFilename());
    }

    public function testGetLastTimePath()
    {
        $target = new MyTarget0();
        $this->assertNotEmpty($target->getLastTimePath());
        $this->assertEquals("my-target.last", basename($target->getLastTimePath()));
    }

    public function testSetLastTimePath()
    {
        $target = new MyTarget0();
        $target->setLastTimePath("/tmp/my-target.last");
        $this->assertNotEmpty($target->getLastTimePath());
        $this->assertEquals("/tmp/my-target.last", $target->getLastTimePath());
    }

    public function testGetLockFilePath()
    {
        $target = new MyTarget0();
        $this->assertNotEmpty($target->getLockFilePath());
        $this->assertEquals("my-target.lock", basename($target->getLockFilePath()));
    }

    public function testSetLockFilePath()
    {
        $target = new MyTarget0();
        $target->setLockFilePath("/tmp/my-target.lock");
        $this->assertNotEmpty($target->getLockFilePath());
        $this->assertEquals("/tmp/my-target.lock", $target->getLockFilePath());
    }

    public function testGetLastRun()
    {
        $target = new MyTarget0();
        $this->assertEquals(0, $target->getLastRun());

        $target->rebuild();
        $this->assertEquals(time(), $target->getLastRun());
    }

    public function testIsLocked()
    {
        $target = new MyTarget0();
        $this->assertFalse($target->isLocked());
    }

    public function testIsUpdated()
    {
        $target = new MyTarget0();
        $this->assertFalse($target->isUpdated());

        $target->rebuild();
        $this->assertTrue($target->isUpdated());
    }

    public function testRebuild()
    {
        $target = new MyTarget0();
        $target->rebuild();
        $this->assertEquals(1, $target->getBuilt());

        $target->rebuild();
        $this->assertEquals(2, $target->getBuilt());

        if (!$target->isUpdated()) {
            $target->rebuild();
        }

        $this->assertEquals(2, $target->getBuilt());
    }

    protected function tearDown(): void
    {
        $target = new MyTarget0();
        $target->cleanup();
    }
}
