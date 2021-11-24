<?php

declare(strict_types=1);

require_once(__DIR__ . "/../vendor/autoload.php");

use UUP\BuildSystem\Tests\Depend;

$t1 = new Depend("T1");
$t2 = $t1->addChild(new Depend("T2"));
$t3 = $t1->addChild(new Depend("T3"));
$t4 = $t2->addChild(new Depend("T4"));
$t6 = $t4->addChild(new Depend("T6"));
$t5 = $t2->addChild(new Depend("T5"));
$t5 = $t3->addChild(new Depend("T5"));
$t7 = $t5->addChild(new Depend("T7"));
$t8 = $t5->addChild(new Depend("T8"));

printf("++ Rebuild node T5:\n");
$t5->getEvaluator()->rebuild();

printf("++ Rebuild complete tree:\n");
$t1->getEvaluator()->rebuild();
