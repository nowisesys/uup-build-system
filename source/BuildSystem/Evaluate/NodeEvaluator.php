<?php

declare(strict_types=1);

namespace UUP\BuildSystem\Evaluate;

use UUP\BuildSystem\Node\NodeInterface;
use UUP\BuildSystem\Target\TargetInterface;

class NodeEvaluator implements TargetInterface
{
    private NodeInterface $node;

    public function __construct(NodeInterface $node)
    {
        $this->node = $node;
    }

    public function getTarget(): TargetInterface
    {
        return $this->node->getTarget();
    }

    public function isUpdated(): bool
    {
        foreach ($this->node->getChildren() as $child) {
            if ($child->getEvaluator()->isUpdated() == false) {
                return false;
            }
        }

        if ($this->node->getTarget()->isUpdated() == false) {
            return false;
        }

        return true;
    }

    public function rebuild(): void
    {
        $this->rebuildParent();
        $this->rebuildTarget();
        $this->rebuildChildren();
    }

    public function getName(): string
    {
        return "evaluator";
    }

    public function getDescription(): string
    {
        return "Evaluate node and its children";
    }

    private function rebuildParent(): void
    {
        foreach ($this->getParents() as $parent) {
            if ($parent->getTarget()->isUpdated() == false) {
                $parent->getTarget()->rebuild();
            }
        }
    }

    private function rebuildTarget()
    {
        if ($this->node->getTarget()->isUpdated() == false) {
            $this->node->getTarget()->rebuild();
        }
    }

    private function rebuildChildren()
    {
        foreach ($this->node->getChildren() as $child) {
            $child->getEvaluator()->rebuild();
        }
    }

    private function getParents(): array
    {
        $parents = [];

        if ($this->node->hasParent()) {
            for ($parent = $this->node->getParent(); $parent; $parent = $parent->getParent()) {
                array_unshift($parents, $parent);
            }
        }

        return $parents;
    }
}
