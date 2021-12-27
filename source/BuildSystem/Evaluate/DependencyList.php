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

use UUP\BuildSystem\Goal\GoalRegistry;
use UUP\BuildSystem\Node\NodeInterface;
use UUP\BuildSystem\Target\TargetInterface;

/**
 * Provides dependency listing.
 *
 * Given an input node from the dependency tree, returns an array with keys being
 * the target and value being a string/array of dependencies. Intended to be used for
 * debugging rules or along with the registry.
 *
 * The returned list resembles a makefile rule listing, with keys being the goals and
 * the right hands side (values) being its dependencies.
 *
 * @author Anders Lövgren (Nowise Systems)
 */
class DependencyList implements TargetInterface
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
        $this->result = $this->getDependencies($this->node);
        $this->updated = true;
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return "list";
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return "Dependency listing for targets";
    }

    /**
     * Returns the dependency listing.
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
     * Add child nodes to registry.
     * @param NodeInterface $node
     * @param GoalRegistry $registry
     */
    private function setRegistry(NodeInterface $node, GoalRegistry $registry): void
    {
        foreach ($node->getChildren() as $child) {
            $registry->addNode($child);
            $this->setRegistry($child, $registry);
        }
    }

    /**
     * Get dependency listing.
     * @param NodeInterface $root
     * @return array
     */
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

    /**
     * Get parents for node.
     * @param NodeInterface $node
     * @return array|false|mixed
     */
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
