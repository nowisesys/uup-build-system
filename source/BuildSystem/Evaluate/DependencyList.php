<?php

declare(strict_types=1);

namespace UUP\BuildSystem\Evaluate;

use UUP\BuildSystem\Goal\GoalRegistry;
use UUP\BuildSystem\Node\NodeInterface;
use UUP\BuildSystem\Target\TargetInterface;

class DependencyList implements TargetInterface
{
    private NodeInterface $node;
    private array $result = [];
    private bool $updated = false;

    public function __construct(NodeInterface $node)
    {
        $this->node = $node;
    }

    public function isUpdated(): bool
    {
        return $this->updated;
    }

    public function rebuild(): void
    {
        $this->result = $this->getDependencies($this->node);
        $this->updated = true;
    }

    public function getName(): string
    {
        return "list";
    }

    public function getDescription(): string
    {
        return "Dependency listing for targets";
    }

    public function getResult(): array
    {
        return $this->result;
    }

    private function setRegistry(NodeInterface $node, GoalRegistry $registry): void
    {
        foreach ($node->getChildren() as $child) {
            $registry->addNode($child);
            $this->setRegistry($child, $registry);
        }
    }

    private function getDependencies(NodeInterface $root): array
    {
        $result = [];

        $registry = new GoalRegistry();
        $registry->addNode($root);

        $this->setRegistry($root, $registry);

        foreach ($registry->getNodes() as $node) {
            $target = $node->getTarget()->getName();
            $parent = $this->getParents($node);

            $result[$target] = $parent;
        }

        ksort($result);
        return $result;
    }

    private function getParents(NodeInterface $node)
    {
        $result = [];

        foreach ($node->getParents() as $parent) {
            $result[] = $parent->getTarget()->getName();
        }

        if (count($result) > 1) {
            return $result;
        } else {
            return current($result);
        }
    }
}
