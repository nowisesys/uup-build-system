<?php

namespace UUP\Tests\BuildSystem\Evaluate;

use PHPUnit\Framework\TestCase;
use UUP\BuildSystem\Evaluate\DependencyList;
use UUP\BuildSystem\Node\DependencyTree;
use UUP\BuildSystem\Tests\Definition;

class DependencyListTest extends TestCase
{
    public function testIsUpdated()
    {
        $list = $this->getDependencyList();
        $this->assertFalse($list->isUpdated());
    }

    public function testRebuild()
    {
        $list = $this->getDependencyList();
        $list->rebuild();
        $this->assertTrue($list->isUpdated());
    }

    public function testGetName()
    {
        $list = $this->getDependencyList();
        $this->assertEquals("root", $list->getName());
    }

    public function testGetType()
    {
        $list = $this->getDependencyList();
        $this->assertEquals("list", $list->getType());
    }

    public function testGetDescription()
    {
        $list = $this->getDependencyList();
        $this->assertEquals("Dependency listing for targets", $list->getDescription());
    }

    public function testGetResult()
    {
        $expect = [
            'T1' => 'root',
            'T2' => 'T1',
            'T3' => 'T1',
            'T4' => 'T2',
            'T5' => [
                0 => 'T2',
                1 => 'T3'
            ],
            'T6' => 'T4',
            'T7' => 'T5',
            'T8' => 'T5',
            'root' => false,
        ];
        $list = $this->getDependencyList();
        $list->rebuild();
        $this->assertEquals($expect, $list->getResult());
    }


    private function getDependencyTree(): DependencyTree
    {
        $tree = new DependencyTree();

        $tree->addDefinition(new Definition("T1"));
        $tree->addDefinition(new Definition("T2", ["T1"]));
        $tree->addDefinition(new Definition("T3", ["T1"]));
        $tree->addDefinition(new Definition("T4", ["T2"]));
        $tree->addDefinition(new Definition("T5", ["T2", "T3"]));
        $tree->addDefinition(new Definition("T6", ["T4"]));
        $tree->addDefinition(new Definition("T7", ["T5"]));
        $tree->addDefinition(new Definition("T8", ["T5"]));

        return $tree;
    }

    private function getDependencyList(): DependencyList
    {
        return new DependencyList(
            $this->getDependencyTree()
        );
    }
}
