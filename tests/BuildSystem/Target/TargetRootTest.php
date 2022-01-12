<?php

namespace UUP\Tests\BuildSystem\Target;

use PHPUnit\Framework\TestCase;
use UUP\BuildSystem\Target\TargetRoot;

class TargetRootTest extends TestCase
{
    public function testGetName()
    {
        $target = new TargetRoot();
        $this->assertEquals("root", $target->getName());
    }

    public function testGetDescription()
    {
        $target = new TargetRoot();
        $this->assertEquals("The target for root node", $target->getDescription());
    }

    public function testIsUpdated()
    {
        $target = new TargetRoot();
        $this->assertTrue($target->isUpdated());
    }

    public function testRebuild()
    {
        $target = new TargetRoot();
        $target->rebuild();
        $this->assertTrue(true);
    }
}
