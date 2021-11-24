<?php

declare(strict_types=1);

namespace UUP\BuildSystem\Node;

use UUP\BuildSystem\Target\TargetInterface;

interface NodeInterface
{
    public function getParent(): NodeInterface;

    public function setParent(NodeInterface $parent): void;

    public function getChildren(): array;

    public function addChild(NodeInterface $node): NodeInterface;

    public function getTarget(): TargetInterface;

    public function getEvaluator(): TargetInterface;

    public function hasParent(): bool;
}
