<?php

namespace UUP\Tests\BuildSystem\Target;

use PHPUnit\Framework\TestCase;
use UUP\BuildSystem\Target\TargetShell;

class TargetShellTest extends TestCase
{
    public function testGetName()
    {
        $target = new TargetShell();
        $this->assertEquals("", $target->getName());
    }

    public function testGetType()
    {
        $target = new TargetShell();
        $this->assertEquals("shell", $target->getType());
    }

    public function testGetDescription()
    {
        $target = new TargetShell();
        $this->assertEquals("Execute commands in a system shell", $target->getDescription());
    }

    public function testIsUpdated()
    {
        $target = new TargetShell();
        $this->assertFalse($target->isUpdated());
    }

    public function testRebuild()
    {
        $target = new TargetShell("", "pwd && ls -l");
        $target->rebuild();     // Output from both commands

        $target = new TargetShell("", "pwd && ls -l >& /dev/null");
        $target->rebuild();     // Output from pwd only

        $target = new TargetShell("", "(pwd && ls -l) >& /dev/null");
        $target->rebuild();     // Silent

        $target = new TargetShell("", "@(pwd && ls -l)");
        $target->rebuild();     // Silent

        $target = new TargetShell("", "@(pwd; ls -l)");
        $target->rebuild();     // Silent

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
