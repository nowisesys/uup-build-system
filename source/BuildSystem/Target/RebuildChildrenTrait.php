<?php

declare(strict_types=1);

namespace UUP\BuildSystem\Target;

/**
 * Defines whether to rebuild child nodes or not.
 *
 * When rebuilding a target node, the default mode of operation is to rebuild all
 * parent nodes, the target node itself and all child nodes. This trait adds support
 * for standard make behavior where only rule dependencies and the rule target gets
 * rebuilt when rebuild children is set to false.
 *
 * @author Anders Lövgren (Nowise Systems)
 */
trait RebuildChildrenTrait
{
    private bool $rebuildChildren = true;

    /**
     * Set whether child nodes of this node should be rebuilt.
     *
     * @param bool $enable True if child nodes should b
     * @return self
     */
    public function setRebuildChildren(bool $enable = true): self
    {
        $this->rebuildChildren = $enable;
        return $this;
    }

    /**
     * Check whether child nodes of this node should be rebuilt.
     * @return bool
     */
    private function shouldRebuildChildren(): bool
    {
        return $this->rebuildChildren;
    }
}
