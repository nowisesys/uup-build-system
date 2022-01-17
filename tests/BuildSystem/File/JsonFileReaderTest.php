<?php

namespace UUP\Tests\BuildSystem\File;

use PHPUnit\Framework\TestCase;
use ReflectionException;
use UUP\BuildSystem\File\JsonFileReader;

class JsonFileReaderTest extends TestCase
{
    use FileReaderTrait;

    /**
     * @throws ReflectionException
     */
    public function testAddDependencies()
    {
        $reader = new JsonFileReader();
        $reader->addDependencies(__DIR__ . '/../../../example/file/input.json');
        $this->assertEquals("UUP\\BuildSystem\\Tests", $reader->getNamespace());
        $this->checkDependencyTree($reader);

        $reader = new JsonFileReader();
        $reader->addDependencies(__DIR__ . '/../../../example/file/implicit.json');
        $this->assertEquals("UUP\\BuildSystem\\Tests\\Implicit", $reader->getNamespace());
        $this->checkDependencyTree($reader);
    }
}
