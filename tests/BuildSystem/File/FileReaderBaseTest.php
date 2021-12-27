<?php

namespace UUP\Tests\BuildSystem\File;

use PHPUnit\Framework\TestCase;
use UUP\BuildSystem\File\FileReaderBase;
use UUP\BuildSystem\Node\DependencyNode;
use UUP\BuildSystem\Node\DependencyTree;
use UUP\BuildSystem\Node\NodeInterface;

class MyFileReader extends FileReaderBase
{
    public function addDependencies(string $filename): void
    {
    }
}

class FileReaderBaseTest extends TestCase
{
    public function testGetNamespace()
    {
        $reader = new MyFileReader();
        $this->assertEmpty($reader->getNamespace());
    }

    public function testSetNamespace()
    {
        $reader = new MyFileReader();
        $reader->setNamespace("Some\\Name\\Space");
        $this->assertEquals("Some\\Name\\Space", $reader->getNamespace());
    }

    public function testGetDependencyTree()
    {
        $reader = new MyFileReader();
        $this->assertInstanceOf(DependencyTree::class, $reader->getDependencyTree());
        $this->assertInstanceOf(DependencyNode::class, $reader->getDependencyTree());
        $this->assertInstanceOf(NodeInterface::class, $reader->getDependencyTree());
    }

    public function testSetDependencyTree()
    {
        $object = new DependencyTree();
        $reader = new MyFileReader();
        $reader->setDependencyTree($object);
        $this->assertSame($object, $reader->getDependencyTree());
    }
}
