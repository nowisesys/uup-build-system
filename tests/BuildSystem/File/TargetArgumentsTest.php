<?php

namespace UUP\Tests\BuildSystem\File;

use PHPUnit\Framework\TestCase;
use UUP\BuildSystem\File\TargetArguments;

class TargetArgumentsTest extends TestCase
{
    public function testConstruct()
    {
        $parser = new TargetArguments(['v1', 123, true]);
        $this->assertEquals(['v1', 123, true], $parser->getArguments());
    }

    public function testGetArguments()
    {
        $parser = new TargetArguments();
        $this->assertEmpty($parser->getArguments());
    }

    public function testSetArguments()
    {
        $parser = new TargetArguments();
        $parser->setArguments(['v1', 123, true]);
        $this->assertEquals(['v1', 123, true], $parser->getArguments());
    }

    /**
     * @dataProvider dataParseString
     */
    public function testParseString(string $arguments, array $expect)
    {
        $parser = new TargetArguments();
        $parser->parseString($arguments);
        $this->assertEquals($expect, $parser->getArguments());
    }

    public function dataParseString(): array
    {
        return [
            ['', []],
            ['""', [""]],

            ['"T1"', ["T1"]],
            ['123', [123]],
            ['12.3', [12.3]],
            ['true', [true]],
            ['false', [false]],

            ['"T1", "T2"', ["T1", "T2"]],
            ['123, "T2"', [123, "T2"]],
            ['12.3, "T2"', [12.3, "T2"]],
            ['true, "T2"', [true, "T2"]],
            ['false, "T2"', [false, "T2"]],

            ['"T1", 123, true', ["T1", 123, true]],
            ['"T1", [123, true]', ["T1", [123, true]]],

            ['T1', ["T1"]],
            ['T1 T2', ["T1", "T2"]],
            ['T1  T2', ["T1", "T2"]],
            ["T1\tT2", ["T1", "T2"]],
            ["T1\tT2 T3", ["T1", "T2", "T3"]],
            ["T1\tT2\tT3", ["T1", "T2", "T3"]],
            ["T1\tT2  T3", ["T1", "T2", "T3"]],

            ['T1,T2', ["T1", "T2"]],
            ['T1, T2', ["T1", "T2"]],
            ['T1,  T2', ["T1", "T2"]],
            ["T1,\tT2", ["T1", "T2"]],
            ["T1\t,T2", ["T1", "T2"]],
            ["T1,\tT2, T3", ["T1", "T2", "T3"]],
            ["T1\t,T2, T3", ["T1", "T2", "T3"]],

            ['"T1", "T2", []', ["T1", "T2", []]],
            ['"T1", "T2", {}', ["T1", "T2", []]],
            ['"T1", "T2", {"P1": true}', ["T1", "T2", ['P1' => true]]],
            ['"T1", "T2", {"P1": true, "P2": 123}', ["T1", "T2", ['P1' => true, 'P2' => 123]]],
            ['"T1", "T2", ["P1"]', ["T1", "T2", ['P1']]],
            ['"T1", "T2", ["P1", "P2"]', ["T1", "T2", ['P1', 'P2']]],
            ['"T1", "T2", ["P1", "P2", {}]', ["T1", "T2", ['P1', 'P2', []]]],
            ['"T1", "T2", ["P1", "P2", {"P3": true}]', ["T1", "T2", ['P1', 'P2', ["P3" => true]]]],
            ['"T1", "T2", ["P1", "P2", {"P3": true, "P4": 123}]', ["T1", "T2", ['P1', 'P2', ["P3" => true, "P4" => 123]]]],
        ];
    }
}
