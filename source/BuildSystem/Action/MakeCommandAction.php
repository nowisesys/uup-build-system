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
use UUP\BuildSystem\Evaluate\DependencyList;
use UUP\BuildSystem\Evaluate\NodeEvaluator;
use UUP\BuildSystem\Evaluate\NodeGraph;
use UUP\BuildSystem\File\FileDetector;
use UUP\BuildSystem\File\FileReaderInterface;
use UUP\BuildSystem\File\JsonFileReader;
use UUP\BuildSystem\File\MakeFileReader;
use UUP\BuildSystem\Generate\TemplateMakefileGenerator;
use UUP\BuildSystem\Node\DependencyTree;

class MakeCommandAction extends ApplicationAction
{
    public function usage(): void
    {
        printf("PHP make (build system command)\n");
        printf("\n");
        printf("Usage: %s makefile1 [...makefiles] [target=name] [type=json]\n", $this->getScript());
        printf("\n");
        printf("Options:\n");
        printf("  target=name:      Make this target.\n");
        printf("  type=str:         The type of makefile (make/json).\n");
        printf("  compat[=bool]:    Enable make compatible mode.\n");
        printf("  generate[=mode]:  Output template makefile (explicit/implicit).\n");
        printf("  recursive:        Recursive scan for makefiles (-r).\n");
        printf("\n");

        parent::usage();

        printf("\n");
        printf("Copyright (C) 2021-%s Nowise Systems\n", date('Y'));
    }

    public function setup(): void
    {
        if ($this->options->hasOption('r')) {
            $this->options->setOption('recursive', true);
        }

        if ($this->options->hasOption('generate')) {
            $this->outputTemplateMakefile();
        }

        foreach ($this->options->getOptions() as $file => $args) {
            if ($this->isMakefile($file)) {
                $this->options->addOption('makefiles', $file);
            }
        }

        if ($this->options->getOption('makefiles', []) == []) {
            $this->options->setOption('makefiles', $this->detectMakeFiles());
        }

        if ($this->options->isMissing('type')) {
            $this->options->setOption('type', $this->detectFileReader());
        }

        if ($this->options->getOption('type', 'make') == 'pbs' ||
            $this->options->getOption('type', 'make') == 'txt') {
            $this->options->setOption('type', 'make');
        }

        $this->options->setObject('reader', $this->createFileReader());
    }

    /**
     * @throws ReflectionException
     */
    public function execute(): void
    {
        $reader = $this->getFileReader();

        foreach ($this->options->getOption('makefiles', []) as $makefile) {
            $reader->addDependencies($makefile);
        }

        $evaluator = $this->getEvaluator();

        if ($this->options->getBoolean('compat')) {
            $evaluator->setRebuildChildren(false);
        }
        if ($this->options->getBoolean('verbose')) {
            $evaluator->setVerbose(true);
        }
        if ($this->options->getBoolean('debug')) {
            $this->showDebugGraph($reader->getDependencyTree());
        }

        $evaluator->rebuild();
    }

    public function getVersion(): string
    {
        return json_decode(
            file_get_contents(__DIR__ . '/../../../composer.json')
        )->version;
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

    private function detectFileReader(): string
    {
        foreach ($this->options->getOption('makefiles', []) as $makefile) {
            if (preg_match('/\.([^.]+)$/', $makefile, $matches)) {
                return $matches[1];
            }
        }

        return 'make';
    }

    private function getNodeGraph(DependencyTree $tree): NodeGraph
    {
        if ($this->options->hasOption('target')) {
            $node = $this->options->getString('target');
            return new NodeGraph($tree->getRegistry()->getNode($node));
        } else {
            return new NodeGraph($tree);
        }
    }

    private function getDependencyList(DependencyTree $tree): DependencyList
    {
        if ($this->options->hasOption('target')) {
            $node = $this->options->getString('target');
            return new DependencyList($tree->getRegistry()->getNode($node));
        } else {
            return new DependencyList($tree);
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

    private function showDebugGraph(DependencyTree $tree): void
    {
        $graph1 = $this->getDependencyList($tree);
        $graph1->rebuild();

        $graph2 = $this->getNodeGraph($tree);
        $graph2->rebuild();

        printf("%s (%s):\n%s\n\n", $graph1->getDescription(), $graph1->getName(), $graph1->getGraph());
        printf("%s (%s):\n%s\n\n", $graph2->getDescription(), $graph2->getName(), $graph2->getGraph());
    }

    private function outputTemplateMakefile(): void
    {
        $generator = new TemplateMakefileGenerator($this->options->getString('type', 'make'));
        $generator->setTargetMode($this->options->getString('generate'));
        $generator->output();
    }

    private function detectMakeFiles(): array
    {
        $detector = new FileDetector();
        $detector->setRecursive($this->options->getBoolean('recursive'));
        return $detector->getMakefiles();
    }
}
