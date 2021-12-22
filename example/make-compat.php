<?php

declare(strict_types=1);

require_once(__DIR__ . "/../vendor/autoload.php");

use UUP\BuildSystem\Node\DependencyTree;
use UUP\BuildSystem\Tests\Definition;

$tree = new DependencyTree();
$tree->addDefinition(new Definition("T1"));
$tree->addDefinition(new Definition("T2", ["T1"]));
$tree->addDefinition(new Definition("T3", ["T1"]));
$tree->addDefinition(new Definition("T4", ["T2"]));
$tree->addDefinition(new Definition("T5", ["T2", "T3"]));
$tree->addDefinition(new Definition("T6", ["T4"]));
$tree->addDefinition(new Definition("T7", ["T5"]));
$tree->addDefinition(new Definition("T8", ["T5"]));

printf("++ Rebuild node T5:\n");
$node = $tree->getRegistry()->getNode("T5");
$node->getEvaluator()->setRebuildChildren(false)->rebuild();

printf("++ Rebuild complete tree:\n");
$tree->getEvaluator()->setRebuildChildren(false)->rebuild();
