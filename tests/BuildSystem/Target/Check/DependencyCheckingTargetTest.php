<?php

namespace UUP\Tests\BuildSystem\Target\Check;

use PHPUnit\Framework\TestCase;
use UUP\BuildSystem\Target\Check\DependencyCheckingTarget;

class MyTarget4 extends DependencyCheckingTarget
{
    public function __construct()
    {
        parent::__construct("/tmp/my-target4", "/tmp");
        parent::setName("my-target4");
    }

    public function initialize()
    {
        touch("/tmp/my-target4");
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

class DependencyCheckingTargetTest extends TestCase
{
    public function testIsUpdated()
    {
        $this->assertFalse($this->target->isUpdated());

        $this->target->rebuild();
        $this->assertTrue($this->target->isUpdated());

        $this->target->setDependencies(['my-target4-dep1', 'my-target4-dep2']);
        $this->assertTrue($this->target->isUpdated());

        touch('/tmp/my-target4-dep1.last', time() + 1);
        $this->assertFalse($this->target->isUpdated());

        touch('/tmp/my-target4.last', time() + 2);
        $this->assertTrue($this->target->isUpdated());

        unlink('/tmp/my-target4-dep1.last');
    }

    public function testGetType()
    {
        $this->assertEquals("dependency-checking", $this->target->getType());
    }

    public function testGetDescription()
    {
        $this->assertEquals("Dependency checking target", $this->target->getDescription());
    }

    public function testSetDependencies()
    {
        $this->target->setDependencies(['test1', 'test2']);
        $this->assertTrue(true);
    }

    public function testSetName()
    {
        $this->target->setName('test1');
        $this->assertEquals('test1', $this->target->getName());
        $this->assertEquals('/tmp/test1.last', $this->target->getLastTimePath());
    }

    protected function setUp(): void
    {
        $this->target = new MyTarget4();
        $this->target->initialize();
    }

    protected function tearDown(): void
    {
        $this->target->cleanup();
    }
}
