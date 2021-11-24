<?php

declare(strict_types=1);

require_once(__DIR__ . "/../vendor/autoload.php");

use UUP\BuildSystem\Evaluate\NodeGraph;
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

$graph = new NodeGraph($tree->getRegistry()->getNode("T3"));
$graph->rebuild();
printf("++ Graph for node T3:\n%s\n", $graph->getGraph());

$graph = new NodeGraph($tree);
$graph->rebuild();
printf("++ Graph for complete tree:\n%s\n", $graph->getGraph());
