<?php

namespace UUP\Tests\BuildSystem\Node;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use UUP\BuildSystem\Goal\GoalRegistry;
use UUP\BuildSystem\Node\DependencyTree;
use UUP\BuildSystem\Target\TargetRoot;
use UUP\BuildSystem\Tests\Definition;

class DependencyTreeTest extends TestCase
{
    public function testHasParent()
    {
        $tree = new DependencyTree();
        $this->assertFalse($tree->hasParent());
    }

    public function testGetTarget()
    {
        $tree = new DependencyTree();
        $this->assertEquals("root", $tree->getTarget()->getName());
    }

    public function testGetRegistry()
    {
        $tree = new DependencyTree();
        $root = current($tree->getRegistry()->getNodes());

        $this->assertInstanceOf(GoalRegistry::class, $tree->getRegistry());
        $this->assertNotEmpty($tree->getRegistry()->getNodes());
        $this->assertCount(1, $tree->getRegistry()->getNodes());
        $this->assertSame($tree, $root);
        $this->assertInstanceOf(TargetRoot::class, $root->getTarget());
    }

    public function testAddDefinition()
    {
        $tree = new DependencyTree();
        $tree->addDefinition(new Definition("T1"));

        $this->assertTrue($tree->getRegistry()->hasNode("T1"));

        $this->expectException(InvalidArgumentException::class);
        $tree->addDefinition(new Definition("T2", ["T3"]));     // T3 is not defined
    }

}
