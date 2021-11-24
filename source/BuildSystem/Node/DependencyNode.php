<?php

declare(strict_types=1);

namespace UUP\BuildSystem\Node;

use UUP\BuildSystem\Evaluate\NodeEvaluator;
use UUP\BuildSystem\Target\TargetInterface;

class DependencyNode implements NodeInterface
{
    private TargetInterface $target;
    private array $parents = [];
    private array $children = [];

    public function __construct(TargetInterface $target, ?NodeInterface $parent = null)
    {
        $this->target = $target;
        $this->addParent($parent);
    }

    public function getParents(): array
    {
        return $this->parents;
    }

    public function addParent(?NodeInterface $parent): void
    {
        if ($parent) {
            $this->parents[] = $parent;
        }
    }

    public function hasParent(): bool
    {
        return !empty($this->parents);
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function addChild(NodeInterface $node): NodeInterface
    {
        $node->addParent($this);
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
