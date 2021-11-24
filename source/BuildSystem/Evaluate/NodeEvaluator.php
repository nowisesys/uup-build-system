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

    private function setParents(NodeInterface $node, array &$parents): void
    {
        foreach ($node->getParents() as $parent) {
            $this->setParents($parent, $parents);
            if (!array_key_exists($parent->getTarget()->getName(), $parents)) {
                $parents[$parent->getTarget()->getName()] = $parent;
            }
        }

        if (!array_key_exists($node->getTarget()->getName(), $parents)) {
            $parents[$node->getTarget()->getName()] = $node;
        }
    }

    private function getParents(): array
    {
        $parents = [];

        foreach ($this->node->getParents() as $parent) {
            $this->setParents($parent, $parents);
        }

        return $parents;
    }
}
