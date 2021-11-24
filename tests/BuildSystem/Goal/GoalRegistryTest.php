<?php

namespace UUP\Tests\BuildSystem\Goal;

use PHPUnit\Framework\TestCase;
use UUP\BuildSystem\Goal\GoalRegistry;
use UUP\BuildSystem\Node\DependencyNode;
use UUP\BuildSystem\Tests\Depend;
use UUP\BuildSystem\Tests\Target;

class GoalRegistryTest extends TestCase
{

    public function testGetNodes()
    {
        $registry = new GoalRegistry();
        $this->assertEmpty($registry->getNodes());
    }

    public function testGetNode()
    {
        $node = $this->getDependencyNode();

        $registry = new GoalRegistry();
        $registry->addNode($node);

        $this->assertSame($node, $registry->getNode("T1"));
    }

    public function testHasNode()
    {
        $node = $this->getDependencyNode();

        $registry = new GoalRegistry();
        $registry->addNode($node);

        $this->assertTrue($registry->hasNode("T1"));
        $this->assertFalse($registry->hasNode("T2"));
    }

    private function getDependencyNode(): DependencyNode
    {
        return new DependencyNode(new Target("T1"));
    }
}
