<?php

namespace UUP\Tests\BuildSystem\Target\Check;

use PHPUnit\Framework\TestCase;
use UUP\BuildSystem\Target\Check\CreateOnceTarget;

class MyTarget2 extends CreateOnceTarget
{
    public function __construct()
    {
        parent::__construct("/tmp/my-target2");
        touch("/tmp/my-target2");
    }

    public function getDescription(): string
    {
        return "My Target 2";
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

class CreateOnceTargetTest extends TestCase
{
    public function testIsUpdated()
    {
        $target = new MyTarget2();
        $this->assertFalse($target->isUpdated());

        $target->rebuild();
        $this->assertTrue($target->isUpdated());

        touch($target->getFilename());
        touch($target->getLastTimePath(), time() - 1);      // Fake rebuild one second ago

        $this->assertTrue($target->isUpdated());
    }

    protected function tearDown(): void
    {
        $target = new MyTarget2();
        $target->cleanup();
    }
}
