<?php

namespace UUP\Tests\BuildSystem\Target\Check;

use PHPUnit\Framework\TestCase;
use UUP\BuildSystem\Target\Check\UpdateModifiedTarget;

class MyTarget3 extends UpdateModifiedTarget
{
    public function __construct()
    {
        parent::__construct("/tmp/my-target3");
        touch("/tmp/my-target3");
    }

    public function getDescription(): string
    {
        return "My Target 3";
    }

    public function cleanup()
    {
        if (file_exists($this->getLastTimePath())) {
            unlink($this->getLastTimePath());
        }
        if (file_exists($this->getLockFilePath())) {
            unlink($this->getLockFilePath());
        }
    }

    protected function perform(): void
    {
    }
}

class UpdateModifiedTargetTest extends TestCase
{
    public function testIsUpdated()
    {
        $target = new MyTarget3();
        $this->assertFalse($target->isUpdated());

        $target->rebuild();
        $this->assertTrue($target->isUpdated());

        touch($target->getFilename());
        touch($target->getLastTimePath(), time() - 1);      // Fake rebuild one second ago

        $this->assertFalse($target->isUpdated());

        $target->rebuild();
        $this->assertTrue($target->isUpdated());
    }

    protected function tearDown(): void
    {
        $target = new MyTarget3();
        $target->cleanup();
    }
}
