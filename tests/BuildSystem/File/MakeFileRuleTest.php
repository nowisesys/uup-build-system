<?php

namespace UUP\Tests\BuildSystem\File;

use PHPUnit\Framework\TestCase;
use UUP\BuildSystem\File\MakeFileRule;

class MakeFileRuleTest extends TestCase
{
    public function testGetName()
    {
        $rule = new MakeFileRule();
        $this->assertEquals("", $rule->getName());
    }

    public function testSetName()
    {
        $rule = new MakeFileRule();
        $rule->setName("test1");
        $this->assertEquals("test1", $rule->getName());
    }

    public function testSetArguments()
    {
        $rule = new MakeFileRule();
        $rule->setArguments("v1 v2");
        $this->assertEquals("v1 v2", $rule->getDefinition()['arguments']);
    }

    public function testSetClass()
    {
        $rule = new MakeFileRule();
        $rule->setClass("Class1");
        $this->assertEquals("Class1", $rule->getDefinition()['class']);
    }

    public function testSetDependencies()
    {
        $rule = new MakeFileRule();
        $rule->setDependencies(["Class1", "Class2"]);
        $this->assertEquals(["Class1", "Class2"], $rule->getDefinition()['dependencies']);
    }

    public function testGetDefinition()
    {
        $rule = new MakeFileRule();
        $this->assertEquals([
            'name' => '',
            'class' => '',
            'arguments' => '',
            'dependencies' => []
        ], $rule->getDefinition());
    }
}
