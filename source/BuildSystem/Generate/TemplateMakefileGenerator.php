<?php

declare(strict_types=1);

namespace UUP\BuildSystem\Generate;

class TemplateMakefileGenerator
{
    private string $type;
    private string $mode;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function setTargetMode(string $mode): void
    {
        $this->mode = $mode;
    }

    public function output(): void
    {
        printf("%s", file_get_contents($this->getTemplatePath()));
    }

    private function getTemplatePath(): string
    {
        switch ($this->mode) {
            case 'implicit':
                return sprintf("%s/../../../example/file/implicit.%s", __DIR__, $this->type);
            case 'explicit':
            default:
                return sprintf("%s/../../../example/file/input.%s", __DIR__, $this->type);
        }
    }
}
