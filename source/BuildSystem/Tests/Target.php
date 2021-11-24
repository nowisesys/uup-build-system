<?php

declare(strict_types=1);

namespace UUP\BuildSystem\Tests;

use UUP\BuildSystem\Target\TargetInterface;

class Target implements TargetInterface
{
    private string $name;
    private bool $updated = false;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function isUpdated(): bool
    {
        printf("Called isUpdated() on %s (updated=%b)\n", $this->name, $this->updated);
        return $this->updated;
    }

    public function rebuild(): void
    {
        printf("Called rebuild() on %s (updated=%b)\n", $this->name, $this->updated);
        $this->updated = true;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return sprintf("Example target %s", $this->name);
    }
}
