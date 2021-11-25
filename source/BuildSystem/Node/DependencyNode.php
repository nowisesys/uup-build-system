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

namespace UUP\BuildSystem\Node;

use UUP\BuildSystem\Evaluate\NodeEvaluator;
use UUP\BuildSystem\Target\TargetInterface;

/**
 * The dependency node.
 *
 * A node has a target (the code to execute), parent and child nodes. Different to
 * common tree nodes, this node can have more than one parent. It's possible to use this
 * class to construct a complete dependency structure from scratch, but for convenience,
 * use the dependency tree or file readers instead.
 *
 * All dependency nodes has a method that returns the evaluator. Use the evaluator for
 * checking if a dependency node is up-to-date or needs to be rebuilt.
 *
 * @author Anders Lövgren (Nowise Systems)
 */
class DependencyNode implements NodeInterface
{
    private TargetInterface $target;
    private array $parents = [];
    private array $children = [];

    /**
     * Constructor.
     * @param TargetInterface $target The target to execute.
     * @param NodeInterface|null $parent Optional parent node.
     */
    public function __construct(TargetInterface $target, ?NodeInterface $parent = null)
    {
        $this->target = $target;
        $this->addParent($parent);
    }

    /**
     * @inheritdoc
     */
    public function getParents(): array
    {
        return $this->parents;
    }

    /**
     * @inheritdoc
     */
    public function addParent(?NodeInterface $parent): void
    {
        if ($parent) {
            $this->parents[] = $parent;
        }
    }

    /**
     * @inheritdoc
     */
    public function hasParent(): bool
    {
        return !empty($this->parents);
    }

    /**
     * @inheritdoc
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @inheritdoc
     */
    public function addChild(NodeInterface $node): NodeInterface
    {
        $node->addParent($this);
        return $this->children[] = $node;
    }

    /**
     * @inheritdoc
     */
    public function getTarget(): TargetInterface
    {
        return $this->target;
    }

    /**
     * @inheritdoc
     */
    public function getEvaluator(): NodeEvaluator
    {
        return new NodeEvaluator($this);
    }
}
