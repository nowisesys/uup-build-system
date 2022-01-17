<?php

namespace UUP\Tests\BuildSystem\Target;

use PHPUnit\Framework\TestCase;
use UUP\BuildSystem\Target\TargetPhony;

class TargetPhonyTest extends TestCase
{
    public function testGetName()
    {
        $target = new TargetPhony("test");
        $this->assertEquals("test", $target->getName());
    }

    public function testGetType()
    {
        $target = new TargetPhony("test");
        $this->assertEquals("phony", $target->getType());
    }

    public function testGetDescription()
    {
        $target = new TargetPhony("test");
        $this->assertEquals("Phony target for test", $target->getDescription());
    }

    public function testIsUpdated()
    {
        $target = new TargetPhony("test");
        $this->assertFalse($target->isUpdated());
    }

    public function testRebuild()
    {
        $target = new TargetPhony("test");
        $target->rebuild();
        $this->assertTrue(true);
    }
}
