<?php

declare(strict_types=1);

require_once(__DIR__ . "/../../vendor/autoload.php");

use UUP\BuildSystem\File\JsonFileReader;

try {
    $reader = new JsonFileReader();
    $reader->addDependencies(__DIR__ . '/input.json');

    $tree = $reader->getDependencyTree();

    printf("++ Rebuild node T5:\n");
    $node = $tree->getRegistry()->getNode("T5");
    $node->getEvaluator()->rebuild();

    printf("++ Rebuild complete tree:\n");
    $tree->getEvaluator()->rebuild();

} catch (ReflectionException $exception) {
    fprintf(STDERR, $exception->getMessage());
}
