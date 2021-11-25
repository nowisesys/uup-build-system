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
 * Evaluate a tree node.
 *
 * This is a core class supporting rebuild of the dependency tree.
 *
 * @author Anders Lövgren (Nowise Systems)
 */
class NodeEvaluator implements TargetInterface
{
    private NodeInterface $node;

    /**
     * Constructor.
     * @param NodeInterface $node The node to evaluate.
     */
    public function __construct(NodeInterface $node)
    {
        $this->node = $node;
    }

    /**
     * Get target for node.
     * @return TargetInterface
     */
    public function getTarget(): TargetInterface
    {
        return $this->node->getTarget();
    }

    /**
     * Check if node is up-to-date.
     *
     * The node is considered updated (won't need rebuild) iff all of its child nodes
     * are updated and the node itself are updated.
     *
     * @return bool
     */
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

    /**
     * Rebuild current node.
     *
     * This method will rebuild all parent nodes (up to root node), current node
     * and all of its child nodes.
     */
    public function rebuild(): void
    {
        $this->rebuildParent();
        $this->rebuildTarget();
        $this->rebuildChildren();
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return "evaluator";
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return "Evaluate node and its children";
    }

    /**
     * Rebuild parent nodes.
     */
    private function rebuildParent(): void
    {
        foreach ($this->getParents() as $parent) {
            if ($parent->getTarget()->isUpdated() == false) {
                $parent->getTarget()->rebuild();
            }
        }
    }

    /**
     * Rebuild current node.
     */
    private function rebuildTarget()
    {
        if ($this->node->getTarget()->isUpdated() == false) {
            $this->node->getTarget()->rebuild();
        }
    }

    /**
     * Rebuild child nodes.
     */
    private function rebuildChildren()
    {
        foreach ($this->node->getChildren() as $child) {
            $child->getEvaluator()->rebuild();
        }
    }

    /**
     * Append parent nodes in array.
     *
     * @param NodeInterface $node
     * @param array $parents
     */
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

    /**
     * Get parent nodes.
     *
     * The ordering is important. The result array is used for rebuilding nodes in
     * order starting with topmost node and descending.
     *
     * @return array
     */
    private function getParents(): array
    {
        $parents = [];

        foreach ($this->node->getParents() as $parent) {
            $this->setParents($parent, $parents);
        }

        return $parents;
    }
}
