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

namespace UUP\BuildSystem\Goal;

use UUP\BuildSystem\Node\NodeInterface;

/**
 * The goal registry.
 *
 * Provides lookup of dependency tree nodes from goal name. The array is a
 * single dimension listing having goal name as lookup key. Works as a hash
 * table where values are references into the dependency tree.
 *
 * @author Anders Lövgren (Nowise Systems)
 */
class GoalRegistry
{
    private array $nodes = [];

    /**
     * Get
     * @return array
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }

    /**
     * Get tree node for goal.
     * @param string $name The goal name.
     * @return NodeInterface
     */
    public function getNode(string $name): NodeInterface
    {
        return $this->nodes[$name];
    }

    /**
     * Check if goal is present.
     * @param string $name The goal name.
     * @return bool
     */
    public function hasNode(string $name): bool
    {
        return array_key_exists($name, $this->nodes);
    }

    /**
     * Add tree node to this registry.
     * @param NodeInterface $node The tree node.
     */
    public function addNode(NodeInterface $node): void
    {
        $this->nodes[$node->getTarget()->getName()] = $node;
    }
}
