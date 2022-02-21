<?php

namespace UUP\Tests\BuildSystem\File;

use PHPUnit\Framework\TestCase;
use ReflectionException;
use UUP\BuildSystem\File\MakeFileReader;

class MakeFileReaderTest extends TestCase
{
    use FileReaderTrait;

    /**
     * @throws ReflectionException
     */
    public function testAddDependenciesInput()
    {
        $reader = new MakeFileReader();
        $reader->addDependencies(__DIR__ . '/../../../example/file/input.make');
        $this->assertEquals("UUP\\BuildSystem\\Tests", $reader->getNamespace());
        $this->checkDependencyTree($reader);
        $this->checkEnvironment();
    }

    /**
     * @throws ReflectionException
     */
    public function testAddDependenciesImplicit()
    {
        $reader = new MakeFileReader();
        $reader->addDependencies(__DIR__ . '/../../../example/file/implicit.make');
        $this->assertEquals("UUP\\BuildSystem\\Tests\\Implicit", $reader->getNamespace());
        $this->checkDependencyTree($reader);
        $this->checkEnvironment();
    }
}
