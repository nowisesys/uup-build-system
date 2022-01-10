<?php

namespace UUP\Tests\BuildSystem\File;

use Generator;
use PHPUnit\Framework\TestCase;
use UUP\BuildSystem\File\FileDetector;

class FileDetectorTest extends TestCase
{
    public function testIsRecursive()
    {
        $detector = new FileDetector();
        $this->assertFalse($detector->isRecursive());
    }

    public function testSetRecursive()
    {
        $detector = new FileDetector();

        $detector->setRecursive(true);
        $this->assertTrue($detector->isRecursive());

        $detector->setRecursive(false);
        $this->assertFalse($detector->isRecursive());
    }

    public function testGetDirectory()
    {
        $detector = new FileDetector();
        $this->assertEquals('.', $detector->getDirectory());
    }

    public function testSetDirectory()
    {
        $detector = new FileDetector();
        $detector->setDirectory('tests');
        $this->assertEquals('tests', $detector->getDirectory());
    }

    public function testGetMakefiles()
    {
        $detector = new FileDetector();
        $this->assertEquals([], $detector->getMakefiles());
    }

    public function testGetGenerator()
    {
        $detector = new FileDetector();
        $this->assertInstanceOf(Generator::class, $detector->getGenerator());
    }

    public function testDirectoryIterator()
    {
        $detector = new FileDetector(__DIR__);
        $this->assertCount(0, $detector->getMakefiles());

        $detector = new FileDetector(__DIR__ . '/../../../example/detect/');
        $this->assertCount(6, $detector->getMakefiles());

        $detector = new FileDetector(__DIR__ . '/../../../');
        $this->assertCount(0, $detector->getMakefiles());
    }

    public function testRecursiveIterator()
    {
        $detector = new FileDetector(__DIR__);
        $detector->setRecursive(true);
        $this->assertCount(0, $detector->getMakefiles());

        $detector = new FileDetector(__DIR__ . '/../../../example/detect/');
        $detector->setRecursive(true);
        $this->assertCount(6, $detector->getMakefiles());

        $detector = new FileDetector(__DIR__ . '/../../../');
        $detector->setRecursive(true);
        $this->assertCount(6, $detector->getMakefiles());
    }
}
