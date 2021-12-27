<?php

namespace UUP\Tests\BuildSystem\Evaluate;

use PHPUnit\Framework\TestCase;
use UUP\BuildSystem\Evaluate\NodeEvaluator;
use UUP\BuildSystem\Node\DependencyNode;
use UUP\BuildSystem\Tests\Target;

class NodeEvaluatorTest extends TestCase
{
    public function testGetTarget()
    {
        $target = new Target("T1");
        $evaluator = $this->getEvaluator($target);
        $this->assertSame($target, $evaluator->getTarget());
    }

    public function testIsUpdated()
    {
        $evaluator = $this->getEvaluator(new Target("T1"));
        $this->assertFalse($evaluator->isUpdated());
    }

    public function testRebuild()
    {
        $evaluator = $this->getEvaluator(new Target("T1"));
        $evaluator->rebuild();
        $this->assertTrue($evaluator->isUpdated());
    }

    public function testGetName()
    {
        $evaluator = $this->getEvaluator(new Target("T1"));
        $this->assertEquals("evaluator", $evaluator->getName());
    }

    public function testGetDescription()
    {
        $evaluator = $this->getEvaluator(new Target("T1"));
        $this->assertEquals("Evaluate node and its children", $evaluator->getDescription());
    }

    private function getEvaluator(Target $target): NodeEvaluator
    {
        return new NodeEvaluator(
            new DependencyNode($target)
        );
    }

    protected function setUp(): void
    {
        ob_start();
    }

    protected function tearDown(): void
    {
        ob_end_clean();
    }
}
