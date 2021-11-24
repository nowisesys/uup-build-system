<?php

declare(strict_types=1);

namespace UUP\BuildSystem\Target;

class TargetRoot implements TargetInterface
{
    public function isUpdated(): bool
    {
        return true;
    }

    public function rebuild(): void
    {
        // ignore
    }

    public function getName(): string
    {
        return "root";
    }

    public function getDescription(): string
    {
        return "The target for root node";
    }
}
