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

namespace UUP\BuildSystem\Action;

use InvalidArgumentException;
use ReflectionException;
use UUP\Application\Command\ApplicationAction;
use UUP\BuildSystem\Evaluate\NodeEvaluator;
use UUP\BuildSystem\File\FileReaderInterface;
use UUP\BuildSystem\File\JsonFileReader;
use UUP\BuildSystem\File\MakeFileReader;

class MakeCommandAction extends ApplicationAction
{
    public function usage(): void
    {
        printf("PHP make (build system make runner/action)\n");
        printf("\n");
        printf("Usage: %s makefile1 [...makefiles] [target=name] [type=json]\n", $this->getScript());
        printf("\n");
        printf("Options:\n");
        printf("  target=name:  Make this target.\n");
        printf("  type=str:     The type of makefile (make/json).\n");
        printf("\n");

        parent::usage();

        printf("\n");
        printf("Copyright (C) 2021 Nowise Systems\n");
    }

    public function setup(): void
    {
        foreach ($this->options->getOptions() as $file => $args) {
            if ($this->isMakefile($file)) {
                $this->options->addOption('makefiles', $file);
            }
        }

        $this->options->setObject('reader', $this->createFileReader());
    }

    /**
     * @throws ReflectionException
     */
    public function execute(): void
    {
        $reader = $this->getFileReader();

        foreach ($this->options->getOption('makefiles') as $makefile) {
            $reader->addDependencies($makefile);
        }

        $this->getEvaluator()->rebuild();
    }

    private function getFileReader(): FileReaderInterface
    {
        return $this->options->getObject('reader');
    }

    private function createFileReader(): FileReaderInterface
    {
        switch ($this->options->getString('type', 'make')) {
            case 'make':
                return new MakeFileReader();
            case 'json':
                return new JsonFileReader();
            default:
                throw new InvalidArgumentException("Unknown type of file reader");
        }
    }

    private function getEvaluator(): NodeEvaluator
    {
        if ($this->options->hasOption('target')) {
            $node = $this->options->getString('target');
            return $this->getNodeEvaluator($node);
        } else {
            return $this->getRootEvaluator();
        }
    }

    private function getRootEvaluator(): NodeEvaluator
    {
        return $this->getFileReader()
            ->getDependencyTree()
            ->getEvaluator();
    }

    private function getNodeEvaluator($node): NodeEvaluator
    {
        return $this->getFileReader()
            ->getDependencyTree()
            ->getRegistry()
            ->getNode($node)
            ->getEvaluator();
    }

    private function isMakefile(string $file): bool
    {
        if (basename($file) == $this->getScript()) {
            return false;
        }
        if (!is_readable($file)) {
            return false;
        }

        return true;
    }
}
