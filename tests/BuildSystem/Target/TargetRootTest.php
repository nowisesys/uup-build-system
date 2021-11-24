<?php

namespace UUP\Tests\BuildSystem\Target;

use PHPUnit\Framework\TestCase;
use UUP\BuildSystem\Target\TargetRoot;

class TargetRootTest extends TestCase
{
    public function testGetName()
    {
        $root = new TargetRoot();
        $this->assertEquals("root", $root->getName());
    }

    public function testGetDescription()
    {
        $root = new TargetRoot();
        $this->assertEquals("The target for root node", $root->getDescription());
    }

    public function testIsUpdated()
    {
        $root = new TargetRoot();
        $this->assertTrue($root->isUpdated());
    }

    public function testRebuild()
    {
        $root = new TargetRoot();
        $root->rebuild();
        $this->assertTrue(true);
    }
}
