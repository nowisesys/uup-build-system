<?php

namespace UUP\Tests\BuildSystem\Target;

use PHPUnit\Framework\TestCase;
use UUP\BuildSystem\Target\TargetBase;

class MyTarget extends TargetBase
{
    public function isUpdated(): bool
    {
        return false;
    }

    public function rebuild(): void
    {
        // ignore
    }
}

class TargetBaseTest extends TestCase
{
    public function testGetName()
    {
        $target = new MyTarget();
        $this->assertEquals("", $target->getName());
    }

    public function testSetName()
    {
        $target = new MyTarget();
        $target->setName("test");
        $this->assertEquals("test", $target->getName());
    }

    public function testGetDependencies()
    {
        $target = new MyTarget();
        $this->assertIsArray($target->getDependencies());
        $this->assertEmpty($target->getDependencies());
    }

    public function testSetDependencies()
    {
        $target = new MyTarget();
        $target->setDependencies(['test1', 'test2']);
        $this->assertIsArray($target->getDependencies());
        $this->assertEquals(['test1', 'test2'], $target->getDependencies());
    }

    public function testGetType()
    {
        $target = new MyTarget();
        $this->assertEquals('abstract', $target->getType());
    }

    public function testGetDescription()
    {
        $target = new MyTarget();
        $this->assertEquals('Abstract target base', $target->getDescription());
    }
}
