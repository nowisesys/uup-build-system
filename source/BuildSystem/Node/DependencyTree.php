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

use InvalidArgumentException;
use UUP\BuildSystem\Goal\GoalDefinition;
use UUP\BuildSystem\Goal\GoalRegistry;
use UUP\BuildSystem\Target\TargetRoot;

/**
 * The dependency tree.
 *
 * Provides a nice interface on top of dependency nodes for adding goal definitions
 * used for constructing the dependency tree. Internally, a goal registry is used to
 * maintain references to tree nodes based on goal names.
 *
 * A newly constructed dependency tree contains a single root node that will become
 * the parent for all added child nodes.
 *
 * @author Anders Lövgren (Nowise Systems)
 */
class DependencyTree extends DependencyNode implements NodeInterface
{
    private GoalRegistry $registry;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(new TargetRoot());
        $this->registry = new GoalRegistry();
        $this->registry->addNode($this);
    }

    /**
     * Get node registry.
     * @return GoalRegistry
     */
    public function getRegistry(): GoalRegistry
    {
        return $this->registry;
    }

    /**
     * Should always return false.
     * @return bool
     */
    public function hasParent(): bool
    {
        return false;
    }

    /**
     * Insert a goal definition.
     *
     * The dependencies from the goal definition is used for lookup of existing nodes
     * from the dependency tree. If inserted target has no existing dependency node, then
     * a new one will be created automatic.
     *
     * @param GoalDefinition $definition
     */
    public function addDefinition(GoalDefinition $definition): void
    {
        $registry = $this->registry;

        if ($definition->hasDependencies() == false) {
            $definition->addDependency($this->getTarget()->getName());
        }

        foreach ($definition->getDependencies() as $name) {
            if ($registry->hasNode($name) == false) {
                throw new InvalidArgumentException("The goal '$name' is not defined");
            }

            $parent = $registry->getNode($name);
            $target = $definition->getTarget();

            if ($registry->hasNode($target->getName())) {
                $target = $registry->getNode($target->getName());
                $parent->addChild($target);
            } else {
                $target = $parent->addChild(new DependencyNode($target));
                $registry->addNode($target);
            }
        }
    }
}
