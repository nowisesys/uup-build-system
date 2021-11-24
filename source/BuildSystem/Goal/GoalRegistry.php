<?php

declare(strict_types=1);

namespace UUP\BuildSystem\Goal;

use UUP\BuildSystem\Node\NodeInterface;

class GoalRegistry
{
    private array $nodes = [];

    public function getNodes(): array
    {
        return $this->nodes;
    }

    public function getNode(string $name): NodeInterface
    {
        return $this->nodes[$name];
    }

    public function hasNode(string $name): bool
    {
        return array_key_exists($name, $this->nodes);
    }

    public function addNode(NodeInterface $node): void
    {
        $this->nodes[$node->getTarget()->getName()] = $node;
    }
}
