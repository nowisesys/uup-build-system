<?php

declare(strict_types=1);

require_once(__DIR__ . "/../vendor/autoload.php");

use UUP\BuildSystem\Tests\Depend;

$t1 = new Depend("T1");
$t2 = new Depend("T2");
$t3 = new Depend("T3");
$t4 = new Depend("T4");
$t5 = new Depend("T5");
$t6 = new Depend("T6");
$t7 = new Depend("T7");
$t8 = new Depend("T8");

$t1->addChild($t2);
$t1->addChild($t3);
$t2->addChild($t4);
$t4->addChild($t6);
$t2->addChild($t5);
$t3->addChild($t5);
$t5->addChild($t7);
$t5->addChild($t8);

printf("++ Rebuild node T5:\n");
$t5->getEvaluator()->rebuild();

printf("++ Rebuild complete tree:\n");
$t1->getEvaluator()->rebuild();
