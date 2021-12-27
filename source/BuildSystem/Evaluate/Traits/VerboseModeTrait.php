<?php

declare(strict_types=1);

namespace UUP\BuildSystem\Evaluate\Traits;

use UUP\BuildSystem\Target\TargetInterface;

trait VerboseModeTrait
{
    private bool $verbose = false;

    /**
     * Check if verbose mode is enabled.
     *
     * @return bool
     */
    public function isVerbose(): bool
    {
        return $this->verbose;
    }

    /**
     * Set verbose mode.
     *
     * @param bool $enable
     */
    public function setVerbose(bool $enable = true): void
    {
        $this->verbose = $enable;
    }

    /**
     * Called before rebuilding target in verbose mode.
     *
     * @param TargetInterface $target The rebuild target.
     */
    public function onVerbose(TargetInterface $target): void
    {
        printf("Making %s (%s)\n", $target->getName(), $target->getDescription());
    }
}
