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
 * Interface for dependency nodes.
 * @author Anders Lövgren (Nowise Systems)
 */
interface NodeInterface
{
    /**
     * Get parent nodes.
     * @return array
     */
    public function getParents(): array;

    /**
     * Add parent node.
     * @param NodeInterface|null $parent
     */
    public function addParent(NodeInterface $parent): void;

    /**
     * Check if node has parents.
     * @return bool
     */
    public function hasParent(): bool;

    /**
     * Get child nodes.
     * @return array
     */
    public function getChildren(): array;

    /**
     * Add child node.
     * @param NodeInterface $node
     * @return NodeInterface
     */
    public function addChild(NodeInterface $node): NodeInterface;

    /**
     * Get target to execute.
     * @return TargetInterface
     */
    public function getTarget(): TargetInterface;

    /**
     * Get evaluator for this node.
     * @return NodeEvaluator
     */
    public function getEvaluator(): TargetInterface;
}
