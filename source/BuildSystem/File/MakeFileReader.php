<?php

declare(strict_types=1);

namespace UUP\BuildSystem\File;

use ReflectionException;
use RuntimeException;

class MakeFileReader extends FileReaderBase implements FileReaderInterface
{
    private MakeFileRule $rule;

    /**
     * @throws ReflectionException
     */
    public function addDependencies(string $filename): void
    {
        if (!is_readable($filename)) {
            throw new RuntimeException("The dependency file is not readable");
        }

        $content = $this->getMakeContent($filename);

        if (array_key_exists('namespace', $content)) {
            $this->setNamespace($content['namespace']);
        }
        if (array_key_exists('targets', $content)) {
            $this->addTargets($content['targets']);
        }
    }

    private function getMakeContent(string $filename): array
    {
        $result = [
            'targets' => []
        ];
        $handle = fopen($filename, "r");

        while ($line = fgets($handle)) {
            $line = trim($line, " \n");

            if (empty($line) || preg_match('/^#/', $line)) {
                continue;
            } elseif (preg_match('/\s*(\w+)\s*:=\s*(.*)\s*/', $line, $matches)) {
                $this->setOptions($matches[1], $matches[2]);
            } elseif (preg_match('/^(\w+)\s*:\s*(.*)/', $line, $matches)) {
                $this->setRule($matches[1], $matches[2]);
            } elseif (preg_match('/^\t(.*)\("(.*)"\)/', $line, $matches)) {
                $this->setTarget($matches[1], $matches[2]);
                $result['targets'][$this->rule->getName()] = $this->rule->getDefinition();
            }
        }

        fclose($handle);

        return $result;
    }

    /**
     * @throws ReflectionException
     */
    private function addTargets(array $targets): void
    {
        foreach ($targets as $target => $options) {
            $definition = $this->getGoalDefinition($target, $options);
            $this->getDependencyTree()->addDefinition($definition);
        }
    }

    private function setOptions(string $key, string $value): void
    {
        switch ($key) {
            case 'NAMESPACE':
                $this->setNamespace($value);
                break;
            default:
                // ignored right now
        }
    }

    private function setRule(string $goal, string $depends): void
    {
        $this->rule = new MakeFileRule($goal);
        $this->rule->setDependencies(
            array_filter(
                preg_split('/[\s]+/', $depends)
            )
        );
    }

    private function setTarget(string $class, string $arguments): void
    {
        $this->rule->setClass($class);
        $this->rule->setArguments($arguments);
    }
}
