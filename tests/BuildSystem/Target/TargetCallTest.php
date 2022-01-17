<?php

namespace UUP\Tests\BuildSystem\Target;

use PHPUnit\Framework\TestCase;
use UUP\BuildSystem\Target\TargetCall;

class TargetCallTest extends TestCase
{
    public function testGetName()
    {
        $target = new TargetCall();
        $this->assertEquals("", $target->getName());
    }

    public function testGetType()
    {
        $target = new TargetCall();
        $this->assertEquals("call", $target->getType());
    }

    public function testGetDescription()
    {
        $target = new TargetCall();
        $this->assertEquals("Evaluate PHP code", $target->getDescription());
    }

    public function testIsUpdated()
    {
        $target = new TargetCall();
        $this->assertFalse($target->isUpdated());
    }

    public function testRebuild()
    {
        $target = new TargetCall("printf('hello world!');");
        $target->rebuild();
        $this->assertTrue(true);
    }

    protected function setUp(): void
    {
        ob_start();     // Start output buffering
    }

    protected function tearDown(): void
    {
        ob_end_clean(); // Discard buffered output
    }
}
