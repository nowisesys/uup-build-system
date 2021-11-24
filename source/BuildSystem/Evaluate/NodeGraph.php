<?php

declare(strict_types=1);

namespace UUP\BuildSystem\Evaluate;

use UUP\BuildSystem\Node\NodeInterface;
use UUP\BuildSystem\Target\TargetInterface;

class NodeGraph implements TargetInterface
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
        $this->result = $this->getChildren($this->node);
        $this->updated = true;
    }

    public function getName(): string
    {
        return "graph";
    }

    public function getDescription(): string
    {
        return "Evaluate node tree structure";
    }

    public function getResult(): array
    {
        return $this->result;
    }

    public function getGraph(): string
    {
        return json_encode($this->result, JSON_PRETTY_PRINT);
    }

    private function getChildren(NodeInterface $node): array
    {
        $result = [];

        foreach ($node->getChildren() as $child) {
            $name = $child->getTarget()->getName();
            $result[$name] = $this->getChildren($child);
        }

        return $result;
    }
}
