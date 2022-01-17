<?php

/*
 * Copyright (C) 2021 Anders Lövgren (Nowise Systems).
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types=1);

namespace UUP\BuildSystem\Evaluate;

use UUP\BuildSystem\Node\NodeInterface;
use UUP\BuildSystem\Target\TargetInterface;

/**
 * Provides a dependency graph.
 * @author Anders Lövgren (Nowise Systems)
 */
class NodeGraph implements TargetInterface
{
    private NodeInterface $node;
    private array $result = [];
    private bool $updated = false;

    /**
     * Constructor.
     * @param NodeInterface $node The node to inspect.
     */
    public function __construct(NodeInterface $node)
    {
        $this->node = $node;
    }

    /**
     * @inheritdoc
     */
    public function isUpdated(): bool
    {
        return $this->updated;
    }

    /**
     * @inheritdoc
     */
    public function rebuild(): void
    {
        $this->result = $this->getChildren($this->node);
        $this->updated = true;
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->node->getTarget()->getName();
    }

    /**
     * @inheritdoc
     */
    public function getType(): string
    {
        return "graph";
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return "Evaluate node tree structure";
    }

    /**
     * Get result graph as array.
     * @return array
     */
    public function getResult(): array
    {
        return $this->result;
    }

    /**
     * Get result graph as JSON string.
     * @return string
     */
    public function getGraph(): string
    {
        return json_encode($this->result, JSON_PRETTY_PRINT);
    }

    /**
     * Recursive list all child nodes.
     * @param NodeInterface $node The current node.
     * @return array
     */
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
