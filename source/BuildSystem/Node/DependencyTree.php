<?php

declare(strict_types=1);

namespace UUP\BuildSystem\Node;

use InvalidArgumentException;
use UUP\BuildSystem\Goal\GoalDefinition;
use UUP\BuildSystem\Goal\GoalRegistry;
use UUP\BuildSystem\Target\TargetRoot;

class DependencyTree extends DependencyNode implements NodeInterface
{
    private GoalRegistry $registry;

    public function __construct()
    {
        parent::__construct(new TargetRoot());
        $this->registry = new GoalRegistry();
        $this->registry->addNode($this);
    }

    public function getRegistry(): GoalRegistry
    {
        return $this->registry;
    }

    public function hasParent(): bool
    {
        return false;
    }

    public function addDefinition(GoalDefinition $definition): void
    {
        if ($definition->hasDependencies() == false) {
            $definition->addDependency($this->getTarget()->getName());
        }

        foreach ($definition->getDependencies() as $name) {
            if ($this->registry->hasNode($name) == false) {
                throw new InvalidArgumentException("The goal '$name' is not defined");
            }

            $node = $this->registry->getNode($name);
            $node = $node->addChild(new DependencyNode($definition->getTarget()));

            $this->registry->addNode($node);
        }
    }
}
