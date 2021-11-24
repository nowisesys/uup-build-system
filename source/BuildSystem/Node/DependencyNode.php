<?php

declare(strict_types=1);

namespace UUP\BuildSystem\Node;

use UUP\BuildSystem\Evaluate\NodeEvaluator;
use UUP\BuildSystem\Target\TargetInterface;

class DependencyNode implements NodeInterface
{
    private ?NodeInterface $parent;
    private TargetInterface $target;
    private array $children = [];

    public function __construct(TargetInterface $target, ?NodeInterface $parent = null)
    {
        $this->parent = $parent;
        $this->target = $target;
    }

    public function getParent(): ?NodeInterface
    {
        return $this->parent;
    }

    public function setParent(NodeInterface $parent): void
    {
        $this->parent = $parent;
    }

    public function hasParent(): bool
    {
        return isset($this->parent);
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function addChild(NodeInterface $node): NodeInterface
    {
        $node->setParent($this);
        return $this->children[] = $node;
    }

    public function getTarget(): TargetInterface
    {
        return $this->target;
    }

    public function getEvaluator(): NodeEvaluator
    {
        return new NodeEvaluator($this);
    }
}
