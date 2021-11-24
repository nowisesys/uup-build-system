<?php

namespace UUP\Tests\BuildSystem\Goal;

use PHPUnit\Framework\TestCase;
use UUP\BuildSystem\Goal\GoalDefinition;
use UUP\BuildSystem\Tests\Definition;
use UUP\BuildSystem\Tests\Target;

class GoalDefinitionTest extends TestCase
{
    public function testHasDependencies()
    {
        $definition = new Definition("T1");
        $this->assertFalse($definition->hasDependencies());

        $definition = new Definition("T1", ["T2", "T3"]);
        $this->assertTrue($definition->hasDependencies());
    }

    public function testGetDependencies()
    {
        $definition = new Definition("T1");
        $this->assertEmpty($definition->getDependencies());

        $definition = new Definition("T1", ["T2", "T3"]);
        $this->assertNotEmpty($definition->getDependencies());
        $this->assertEquals(["T2", "T3"], $definition->getDependencies());
    }

    public function testSetDependencies()
    {
        $definition = new Definition("T1");
        $definition->setDependencies(["T2", "T3"]);

        $this->assertNotEmpty($definition->getDependencies());
        $this->assertEquals(["T2", "T3"], $definition->getDependencies());
    }

    public function testAddDependency()
    {
        $definition = new Definition("T1");
        $definition->addDependency("T2");
        $definition->addDependency("T3");

        $this->assertNotEmpty($definition->getDependencies());
        $this->assertEquals(["T2", "T3"], $definition->getDependencies());
    }

    public function testGetTarget()
    {
        $target = new Target("T1");

        $definition = new GoalDefinition();
        $this->assertNull($definition->getTarget());

        $definition = new GoalDefinition($target);
        $this->assertNotNull($definition->getTarget());
        $this->assertSame($target, $definition->getTarget());
    }

    public function testSetTarget()
    {
        $target = new Target("T1");

        $definition = new GoalDefinition();
        $definition->setTarget($target);

        $this->assertNotNull($definition->getTarget());
        $this->assertSame($target, $definition->getTarget());
    }
}
