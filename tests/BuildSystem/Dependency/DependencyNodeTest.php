<?php

namespace UUP\Tests\BuildSystem\Dependency;

use PHPUnit\Framework\TestCase;
use UUP\BuildSystem\Node\DependencyNode;
use UUP\BuildSystem\Node\NodeInterface;
use UUP\BuildSystem\Evaluate\NodeEvaluator;
use UUP\BuildSystem\Target\TargetInterface;
use UUP\BuildSystem\Tests\Target;

class DependencyNodeTest extends TestCase
{
    public function testConstruct()
    {
        $target = new Target("T1");
        $node = new DependencyNode($target);

        $this->assertSame($target, $node->getTarget());
        $this->assertFalse($node->hasParent());
        $this->assertInstanceOf(NodeInterface::class, $node);
    }

    public function testGetParent()
    {
        $target = new Target("T1");
        $parent = new DependencyNode($target);

        $node = new DependencyNode($target, $parent);

        $this->assertTrue($node->hasParent());
        $this->assertSame($parent, $node->getParent());
    }

    public function testSetParent()
    {
        $target = new Target("T1");
        $parent = new DependencyNode($target);

        $node = new DependencyNode($target);
        $node->setParent($parent);

        $this->assertTrue($node->hasParent());
        $this->assertSame($parent, $node->getParent());
    }

    public function testHasParent()
    {
        $target = new Target("T1");
        $parent = new DependencyNode($target);

        $node = new DependencyNode($target);

        $this->assertFalse($node->hasParent());
        $node->setParent($parent);
        $this->assertTrue($node->hasParent());
    }

    public function testGetChildren()
    {
        $target = new Target("T1");
        $node = new DependencyNode($target);

        $this->assertIsArray($node->getChildren());
        $this->assertEmpty($node->getChildren());
    }

    public function testAddChild()
    {
        $target = new Target("T1");
        $node1 = new DependencyNode($target);
        $node2 = new DependencyNode($target);
        $node1->addChild($node2);

        $this->assertIsArray($node1->getChildren());
        $this->assertNotEmpty($node1->getChildren());

        $this->assertIsArray($node2->getChildren());
        $this->assertEmpty($node2->getChildren());

        $this->assertSame($node1, $node2->getParent());
    }

    public function testGetTarget()
    {
        $target = new Target("T1");
        $node = new DependencyNode($target);

        $this->assertSame($target, $node->getTarget());
    }

    public function testGetEvaluator()
    {
        $target = new Target("T1");
        $node = new DependencyNode($target);

        $this->assertNotNull($node->getEvaluator());
        $this->assertInstanceOf(TargetInterface::class, $node->getEvaluator());
        $this->assertInstanceOf(NodeEvaluator::class, $node->getEvaluator());
    }
}
