<?php

declare(strict_types=1);

namespace UUP\BuildSystem\Target;

interface TargetInterface
{
    public function isUpdated(): bool;

    public function rebuild(): void;

    public function getName(): string;

    public function getDescription(): string;
}
