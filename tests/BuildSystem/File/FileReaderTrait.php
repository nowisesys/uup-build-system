<?php

declare(strict_types=1);

namespace UUP\Tests\BuildSystem\File;

use UUP\BuildSystem\File\FileReaderInterface;

trait FileReaderTrait
{
    private function checkDependencyTree(FileReaderInterface $reader)
    {
        $registry = $reader->getDependencyTree()->getRegistry();
        $targets = function (array $items): array {
            return array_map(fn($item) => $item->getTarget()->getName(), $items);
        };

        $this->assertTrue($registry->hasNode("T1"));
        $this->assertTrue($registry->hasNode("T2"));
        $this->assertTrue($registry->hasNode("T3"));
        $this->assertTrue($registry->hasNode("T4"));
        $this->assertTrue($registry->hasNode("T5"));
        $this->assertTrue($registry->hasNode("T6"));
        $this->assertTrue($registry->hasNode("T7"));
        $this->assertTrue($registry->hasNode("T8"));

        $this->assertCount(2, $registry->getNode("T1")->getChildren());
        $this->assertCount(2, $registry->getNode("T2")->getChildren());
        $this->assertCount(1, $registry->getNode("T3")->getChildren());
        $this->assertCount(1, $registry->getNode("T4")->getChildren());
        $this->assertCount(2, $registry->getNode("T5")->getChildren());
        $this->assertCount(0, $registry->getNode("T6")->getChildren());
        $this->assertCount(0, $registry->getNode("T7")->getChildren());
        $this->assertCount(0, $registry->getNode("T8")->getChildren());

        $this->assertCount(1, $registry->getNode("T1")->getParents());
        $this->assertCount(1, $registry->getNode("T2")->getParents());
        $this->assertCount(1, $registry->getNode("T3")->getParents());
        $this->assertCount(1, $registry->getNode("T4")->getParents());
        $this->assertCount(2, $registry->getNode("T5")->getParents());
        $this->assertCount(1, $registry->getNode("T6")->getParents());
        $this->assertCount(1, $registry->getNode("T7")->getParents());
        $this->assertCount(1, $registry->getNode("T8")->getParents());

        $this->assertEquals(["T2", "T3"], $targets($registry->getNode("T1")->getChildren()));
        $this->assertEquals(["T4", "T5"], $targets($registry->getNode("T2")->getChildren()));
        $this->assertEquals(["T5"], $targets($registry->getNode("T3")->getChildren()));
        $this->assertEquals(["T6"], $targets($registry->getNode("T4")->getChildren()));
        $this->assertEquals(["T7", "T8"], $targets($registry->getNode("T5")->getChildren()));
        $this->assertEquals([], $targets($registry->getNode("T6")->getChildren()));
        $this->assertEquals([], $targets($registry->getNode("T7")->getChildren()));
        $this->assertEquals([], $targets($registry->getNode("T8")->getChildren()));

        $this->assertEquals(["root"], $targets($registry->getNode("T1")->getParents()));
        $this->assertEquals(["T1"], $targets($registry->getNode("T2")->getParents()));
        $this->assertEquals(["T1"], $targets($registry->getNode("T3")->getParents()));
        $this->assertEquals(["T2"], $targets($registry->getNode("T4")->getParents()));
        $this->assertEquals(["T2", "T3"], $targets($registry->getNode("T5")->getParents()));
        $this->assertEquals(["T4"], $targets($registry->getNode("T6")->getParents()));
        $this->assertEquals(["T5"], $targets($registry->getNode("T7")->getParents()));
        $this->assertEquals(["T5"], $targets($registry->getNode("T8")->getParents()));
    }

    private function checkEnvironment()
    {
        $this->assertTrue($_ENV["PBS_MAKE_DEBUG"]);
        $this->assertTrue($_ENV["PBS_MAKE_VERBOSE"]);
        $this->assertEquals("production", $_ENV["CONVERSION"]);
    }

    protected function setUp(): void
    {
        $_ENV = [
            'ANOTHER' => 123,
            'CONVERSION' => 'replaced'
        ];
    }
}
